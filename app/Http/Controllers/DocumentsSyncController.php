<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Services\RobotApi;
use App\Traits\ResponseTrait;
use DateTime;
use Illuminate\Support\Facades\Auth;

class DocumentsSyncController extends Controller
{
	use ResponseTrait;

	public function start(Request $request)
	{
		try {
			$user = Auth::user();

			// do not sync in development environment
			if (env('APP_ENV') == 'development' || $user->roles[0]->name == env('ES_NAME_ROL_SUPER_ADMIN')) {
				return $this->responseSuccess(['status' => 'done']);
			}

			$companies = $user->companies->filter(function ($company) {
				return $company->sii_is_ok;
			});

			foreach ($companies as $company) {
				$this->_startDocumentsSync($company);
			}

			$someStarted = !!$companies->first(function ($company) {
				return $company->documents_sync_status == 'started';
			});

			return $this->responseSuccess([
				'status' => $someStarted ? 'started' : 'done',
			]);
		} catch (\Exception $e) {
			return $this->responseError(null, $e->getMessage());
		}
	}

	private function _startDocumentsSync($company)
	{
		$documentsSyncAt = new DateTime($company->documents_sync_at ?: '2023-11-01 00:00:00');
		$today = new DateTime();

		$shouldStartSync = null;

		if (!$company->documents_sync_status || $company->documents_sync_status == 'done') {
			$shouldStartSync = $documentsSyncAt->format('Y-m-d') < $today->format('Y-m-d');
		} else if ($company->documents_sync_status == 'started') {
			$interval = $today->diff(new DateTime($company->documents_sync_started_at));
			$hoursDiff = $interval->h + ($interval->days * 24);
			$shouldStartSync = $hoursDiff >= 3;
		}

		if ($shouldStartSync) {
			$api = new RobotApi();
			$periods = [];
			$todayPeriodStr = $today->format('Y-m');
			$currPeriodStr = null;
			do {
				$currPeriodStr = $documentsSyncAt->format('Y-m');
				list($year, $month) = explode('-', $currPeriodStr);

				$periods[] = ['month' => $month, 'year' => $year];
				$documentsSyncAt->modify('+1 month');
			} while ($currPeriodStr < $todayPeriodStr);

			foreach ($periods as $period) {
				$api->startGetDocuments($company, $period['year'], $period['month']);
			}

			$company->documents_sync_status = 'started';
			$company->documents_sync_started_at = $today->format('Y-m-d H:i:s');
			$company->save();
		}
	}

	public function check(Request $request)
	{
		if (env('APP_ENV') == 'development') {
			$someStarted = false;
		} else {
			$user = Auth::user();
			$someStarted = !!$user->companies->first(function ($company) {
				return $company->documents_sync_status == 'started';
			});
		}
		return $this->responseSuccess([
			'status' => $someStarted ? 'started' : 'done',
		]);
	}
}
