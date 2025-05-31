<?php

namespace App\Repositories;

use App\Models\Document;
use App\Models\DocumentDetail;
use App\Models\DocumentStatus;
use App\Models\DocumentType;
use App\Models\ExternalDocument;
use App\Models\IntegrationSII;
use App\Modules\WasabilMailtray\Models\MailTray;
use App\Models\Status;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DocumentRepository
{
	public $document;

	public function __construct()
	{
		$this->document = new Document();
	}

	public function getById($uuid)
	{
		return Document::where('uuid', $uuid)->first();
	}

	public function getFiltersDocuments($email, $rol)
	{
		return $this->document->getFiltersDocuments($email, $rol);
	}

	public function get($uuid, $email, $rol, $action)
	{
		return $this->document->get($uuid, $email, $rol, $action);
	}

	public function createorupdate($data, $rol, $is_replacement_user, $id_replacement_user)
	{
		return $this->document->createorupdate($data, $rol, $is_replacement_user, $id_replacement_user);
	}

	public function list(array $opts)
	{
		$opts = filterOptionsArray($opts, [
			'perPage' => ['int', env('PAGINATION_RECORDS')],
			'page' => ['int', 1],
			'name' => 'string',
			'rut' => 'string',
			'typeId' => 'int',
			'status' => ['int', -1],
			'company' => ['int', -1],
			'includeExternalDocuments' => 'boolean',
			'document' => 'string',
			'user' => 'object'
		]);
		$opts['page'] = max($opts['page'], 1);
		$opts['perPage'] = min(max($opts['perPage'], 1), 50);

		$user = $opts['user'];

		$builder = Document::select(
			DB::raw('0 AS is_external'),
			Document::TABLE . '.document',
			Document::TABLE . '.uuid',
			Document::TABLE . '.document_date',
			Document::TABLE . '.company_id',
			Document::TABLE . '.currency_id',
			Document::TABLE . '.type_dte_id',
			Document::TABLE . '.user_id',
			Document::TABLE . '.supplier_id',
			Supplier::TABLE . '.name AS supplier_name',
			Supplier::TABLE . '.rut AS supplier_rut',
			Document::TABLE . '.status_id',
			Document::TABLE . '.folio',
			Document::TABLE . '.type_id',
			Document::TABLE . '.supplier_current_address',
			Document::TABLE . '.supplier_current_city_location',
			Document::TABLE . '.supplier_current_commune_name',
			Document::TABLE . '.supplier_current_giro',
			Document::TABLE . '.is_replacement_user',
			Document::TABLE . '.id_replacement_user',
			Document::TABLE . '.last_user_id',
			Document::TABLE . '.exchange_is_error_exchange',
			Document::TABLE . '.exchange_json_rate_response',
			Document::TABLE . '.exchange_json_rate_current',
			Document::TABLE . '.exchange_send_notification',
			Document::TABLE . '.exchange_send_notification_message',
			Document::TABLE . '.exchange_rate',
			Document::TABLE . '.exchange_is_ok_transaction',
			Document::TABLE . '.current_nsubtotal',
			Document::TABLE . '.current_niva',
			Document::TABLE . '.current_ntotal',
			Document::TABLE . '.sent_nsubtotal',
			Document::TABLE . '.sent_niva',
			Document::TABLE . '.sent_ntotal',
			Document::TABLE . '.sent_currency_id',
			Document::TABLE . '.last_response_sii_message',
			Document::TABLE . '.scheduled_frequency',
			Document::TABLE . '.scheduled_repeat',
			Document::TABLE . '.scheduled_from_date',
			Document::TABLE . '.scheduled_to_date',
			Document::TABLE . '.scheduled_next',
			Document::TABLE . '.document_parent',
			Document::TABLE . '.up_down_id',
			Document::TABLE . '.created_at',
			Document::TABLE . '.updated_at'
		);

		$builder->join(Supplier::TABLE, Document::TABLE . '.supplier_id', '=', Supplier::TABLE . '.id');

		if ($user) {
			$userCompanies = DB::table('companies')
				->join('companies_users', 'companies.id', '=', 'companies_users.company_id')
				->where('companies_users.user_id', $user->id)
				->select('companies.id', 'companies.sii_is_ok')
				->get();

			if ($opts['company'] > 0) {
				//check if companyId is included in companies array
				$company = $userCompanies->where('id', $opts['company'])->first();
				if ($company) {
					$builder->where(Document::TABLE . '.company_id', $opts['company']);
				}
			} else {
				$companiesIds = $userCompanies->pluck('id')->toArray();
				$builder->whereIn(Document::TABLE . '.company_id', $companiesIds);
			}
		} else {
			$userCompanies = null;
			if ($opts['company'] > 0) {
				$builder->where(Document::TABLE . '.company_id', $opts['company']);
			}
		}

		if ($opts['status'] > 0) {
			$builder->where(Document::TABLE . '.status_id', $opts['status']);
		}

		if ($opts['typeId'] > 0) {
			$builder->where('type_id', $opts['typeId']);
		} else {
			$builder->where('type_id', '<>', DocumentType::PROGRAMMED);
		}

		if ($opts['document']) {
			$builder->where(function ($query) use ($opts) {
				$query->where(Document::TABLE . '.document', $opts['document'])
					->orWhere(Document::TABLE . '.folio', $opts['document']);
			});
		}

		if ($opts['name']) {
			$builder->where(Supplier::TABLE . '.name', 'like',  '%' . $opts['name'] . '%');
		}

		if ($opts['rut']) {
			$builder->where(Supplier::TABLE . '.rut', $opts['rut']);
		}

		if ($opts['includeExternalDocuments']) {
			$builder2 = $this->_makeExternalDocumentsBuilder($opts, $userCompanies);
			if ($builder2) $builder->union($builder2);
		}

		$results = $builder
			->with(['status', 'supplier', 'currency', 'type', 'situation', 'company'])
			->orderBy('document_date', 'desc')
			->paginate($opts['perPage'], ['*'], 'page', $opts['page']);

		$results->appends([
			'name' => $opts['name'],
			'rut' => $opts['rut'],
			'status' => $opts['status'],
			'company' => $opts['company'],
			'perPage' => $opts['perPage'],
			'page' => $opts['page']
		]);

		return $results;
	}

	private function _makeExternalDocumentsBuilder(array $opts, $userCompanies)
	{
		$user = $opts['user'];
		if (!$user) return null;

		if ($opts['status'] > 0 && $opts['status'] != 3) return null;
		if ($opts['typeId'] > 1) return null;
		if ($user->roles[0]->name == env('ES_NAME_ROL_SUPER_ADMIN')) return null;

		$selectedCompaniesIds = [];
		if ($opts['company'] > 0) {
			$company = $userCompanies->where('id', $opts['company'])->first();
			if ($company && $company->sii_is_ok) {
				$selectedCompaniesIds = [$opts['company']];
			}
		} else {
			$selectedCompaniesIds = $userCompanies->where('sii_is_ok', 1)->pluck('id')->toArray();
		}

		if (!count($selectedCompaniesIds)) return null;

		$builder = ExternalDocument::select(
			DB::raw('1 AS is_external'),
			DB::raw('NULL AS document'),
			DB::raw('NULL AS uuid'),
			'issued_date AS document_date',
			'company_id AS company_id',
			DB::raw('1 AS currency_id'),
			DB::raw('NULL AS type_dte_id'),
			DB::raw('NULL AS user_id'),
			DB::raw('NULL AS supplier_id'),
			'social_reason AS supplier_name',
			'provider_rut AS supplier_rut',
			DB::raw(DocumentStatus::ISSUED . ' AS status_id'),
			'folio AS folio',
			DB::raw(DocumentType::MANUAL . ' AS type_id'),
			DB::raw('NULL AS supplier_current_address'),
			DB::raw('NULL AS supplier_current_city_location'),
			DB::raw('NULL AS supplier_current_commune_name'),
			DB::raw('NULL AS supplier_current_giro'),
			DB::raw('NULL AS is_replacement_user'),
			DB::raw('NULL AS id_replacement_user'),
			DB::raw('NULL AS last_user_id'),
			DB::raw('NULL AS exchange_is_error_exchange'),
			DB::raw('NULL AS exchange_json_rate_response'),
			DB::raw('NULL AS exchange_json_rate_current'),
			DB::raw('NULL AS exchange_send_notification'),
			DB::raw('NULL AS exchange_send_notification_message'),
			DB::raw('NULL AS exchange_rate'),
			DB::raw('NULL AS exchange_is_ok_transaction'),
			DB::raw('exempt_amount + net_amount AS current_nsubtotal'),
			DB::raw('total_amount - net_amount AS current_niva'),
			'total_amount AS current_ntotal',
			DB::raw('exempt_amount + net_amount AS sent_nsubtotal'),
			DB::raw('total_amount - net_amount AS sent_niva'),
			'total_amount AS sent_ntotal',
			DB::raw('1 AS sent_currency_id'),
			DB::raw('NULL AS last_response_sii_message'),
			DB::raw('NULL AS scheduled_frequency'),
			DB::raw('NULL AS scheduled_repeat'),
			DB::raw('NULL AS scheduled_from_date'),
			DB::raw('NULL AS scheduled_to_date'),
			DB::raw('NULL AS scheduled_next'),
			DB::raw('NULL AS document_parent'),
			DB::raw('NULL AS up_down_id'),
			DB::raw('issued_date AS created_at'),
			DB::raw('issued_date AS updated_at'),
		);

		$builder->whereIn(ExternalDocument::TABLE . '.company_id', $selectedCompaniesIds);

		if ($opts['document']) {
			$builder->where(ExternalDocument::TABLE . '.folio', $opts['document']);
		}
		if ($opts['name']) {
			$builder->where(ExternalDocument::TABLE . '.social_reason', 'like',  '%' . $opts['name'] . '%');
		}
		if ($opts['rut']) {
			$builder->where(ExternalDocument::TABLE . '.provider_rut', $opts['rut']);
		}

		return $builder;
	}

	public function changesituation(array $arr, $rol)
	{
		return $this->document->changesituation($arr, $rol);
	}

	public function generateDocumentByMailTray(MailTray $mailtray)
	{
		$mailtrayId = $mailtray->document;
		$mailtrayData = $mailtray->data;
		$cab = $mailtrayData['cab'];
		$details = $mailtrayData['det'];

		Log::channel('trays')->info('MAKING DOCUMENT - MAIL TRAY ID: ' . $mailtrayId);

		try {
			DB::beginTransaction();

			$document = new Document();
			$documentId = $document->generateKeyDocument();

			$document->document = $documentId;
			$document->uuid = Str::uuid()->toString();
			$document->document_date = $cab['document_date'];
			$document->currency_id = $cab['currency_id'];
			$document->company_id =  $cab['company_id'];
			$document->sent_currency_id = 1;
			$document->type_dte_id = 5;
			$document->status_id = DocumentStatus::SENDING;
			$document->up_down_id = Status::ACTIVE;
			$document->type_id = DocumentType::MAILTRAY;
			$document->user_id = $cab['mailtrayuserid'];
			$document->supplier_id = $cab['supplier_id'];

			$document->is_replacement_user = 0;
			$document->id_replacement_user = 0;
			$document->last_user_id = $cab['mailtrayuserid'];

			$document->supplier_current_address = $cab['supplier_current_address'];
			$document->supplier_current_city_location = $cab['supplier_current_city_location'];
			$document->supplier_current_commune_name = $cab['supplier_current_commune_name'];
			$document->supplier_current_giro = $cab['supplier_current_giro'] ?? Supplier::DEFAULT_GIRO;

			$integrationSii = new IntegrationSII();

			$exchange = $integrationSii->getExchange([
				'document' => $document->document,
				'document_date' => $document->document_date,
				'currency_id' => $document->currency_id
			]);

			$document->exchange_is_error_exchange = $exchange['exchange_is_error_exchange'];
			$document->exchange_json_rate_current = $exchange['exchange_json_rate_current'];
			$document->exchange_send_notification = $exchange['exchange_send_notification'];
			$document->exchange_send_notification_message = $exchange['exchange_send_notification_message'];
			$document->exchange_json_rate_response = $exchange['exchange_json_rate_response'];
			$document->exchange_rate = $exchange['exchange_rate'];
			$document->exchange_is_ok_transaction = $exchange['exchange_is_ok_transaction'];

			$document->scheduled_frequency = null;
			$document->scheduled_repeat = null;
			$document->scheduled_from_date = null;
			$document->scheduled_to_date = null;
			$document->scheduled_next = null;
			$document->document_parent = '';
			$document->mailtray_document = $mailtray->document;

			$document->save();

			DocumentDetail::createDocumentDetail($document, $details);

			Log::channel('trays')->info('MAKING DOCUMENT SUCCESS: ' . $documentId);
			Log::channel("trays")->info("ENVIANDO A SII: " . $documentId);

			$document->generateDocumentInIntragration($document);

			Log::channel("trays")->info(" FINALIZANDO ENVIO A SII  " .  $document->document);

			$mailtray->type = 'processed';
			$mailtray->save();

			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();

			Log::channel('trays')->error('MAKING DOCUMENT ERROR: ' . $e->getMessage() . ' :: Error trace >> ' . $e->getTraceAsString() . ' :: MailTray data >> ' . json_encode($mailtrayData));
			$mailtray->type = 'error';
			$mailtray->save();
		}
	}

	public function delete($uuid)
	{
		return Document::where('uuid', $uuid)->delete();
	}
}
