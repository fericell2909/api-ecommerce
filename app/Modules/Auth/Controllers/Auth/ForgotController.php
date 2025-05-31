<?php

namespace App\Modules\Auth\Controllers\Auth;

use App\Modules\Auth\Controllers\Auth\Controller;
use App\Jobs\SendEmail;
use App\Modules\Auth\Repositories\ForgotRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Modules\Auth\Requests\ForgotRequest;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\Log;

class ForgotController extends Controller
{
	/**
	 * Response trait to handle return responses.
	 */
	use ResponseTrait, HelperTrait;

	/**
	 * Auth related functionalities.
	 *
	 * @var ForgotRepository
	 */
	public $forgotRepository;
	private $templateforgotpassword;
	/**
	 * Create a new ForgotController instance.
	 *
	 * @return void
	 */
	public function __construct(ForgotRepository $ar)
	{
		$this->middleware('auth:api', ['except' => ['forgot']]);
		$this->forgotRepository = $ar;
		$this->templateforgotpassword = 'mails.forgotpassword';
	}

	/**
	 * @OA\POST(
	 *     path="/api/auth/forgot/password",
	 *     tags={"Gestión de Usuarios"},
	 *     summary="HU-017 Cambiar Contraseña(Enviar Email)",
	 *     description="Envía un correo al usuario con un enlace para cambiar su contraseña",
     *     security={{}},
	 *     @OA\RequestBody(
	 *          @OA\JsonContent(
	 *              type="object",
	 *              @OA\Property(property="email", type="string", example="example@example.com"),
	 *          ),
	 *      ),
	 *      @OA\Response(response=200, description="Correo Enviado Extiosamente" , @OA\JsonContent(ref="#/components/schemas/ForgotPassword200Schema")),
	 *      @OA\Response(response=400, description="Error en Solicitud", @OA\JsonContent(ref="#/components/schemas/ForgotPassword400Schema")),
	 *      @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
	 * )
	 */
	public function forgot(ForgotRequest $request): JsonResponse
	{

		try {

			$requestData = $request->only('email');

			$passwordreset = $this->forgotRepository->forgot($requestData);

			//
			if ($this->forgotRepository->exist($passwordreset)) {
				$link = env('APP_URL_FRONT') . '/reset/password/' . $passwordreset->token;
				$mail = sampleMail();
				$mail->user->email = $passwordreset->email;
				$mail->title = "Recupera tu contraseña de ".env('APP_NAME');
				$mail->subject = "Recupera tu contraseña de ".env('APP_NAME');
				$mail->body = "Hemos recibido una solicitud para recuperar tu contraseña. Si fuiste tú, haz click en el siguiente enlace para establecer una nueva:";
				$mail->link = $link;
				$mail->textBtn = "Restablecer contraseña";
				$mail->sub_body = "Si no hiciste esta solicitud, por favor ignora este correo o ponte en contacto con nuestro soporte.<br/><br/>";

                if(mailSend($mail)) {
                    return $this->responseSuccess(null, "Correo Enviado Extiosamente", Response::HTTP_OK);
                }

                return $this->responseError(null, "Error al reiniciar contraseña.", Response::HTTP_FORBIDDEN);
			}

			return $this->responseError(null, "Error al reiniciar contraseña.", Response::HTTP_FORBIDDEN);

        } catch (\Exception $e) {

			Log::error("ERROR :::  Controlador : ForgotController ::: Metodo : forgot ::: Email : " . $requestData['email']);
			Log::error($e->getMessage());
            Log::error($e->getTraceAsString());;

            return $this->responseWithErrorEx(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, $e->getTraceAsString());
		}
	}
}
