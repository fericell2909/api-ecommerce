<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Document;
use App\Modules\WasabilMailtray\Models\MailTray;
use App\Repositories\DocumentRepository;

class MailTrayGenerateDocument extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:mail_tray_generate_document';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Genera documentos de los datos de la bandeja de mails y los guarda en la tabla documents y envía a SII';

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{


		$mailtrays = MailTray::where('type', 'registered')->get();

		// exit if there are no mailtrays
		if (count($mailtrays) === 0) return 0;

		Log::channel("trays")->error("PROCESO MAILTRAYS : INICIO");
		$documentRepository = new DocumentRepository();

		foreach ($mailtrays as $mailtray) {
			$documentRepository->generateDocumentByMailTray($mailtray);
		}

		Log::channel("trays")->info("PROCESO MAILTRAYS : TERMINO");
		return 0;
	}
}
