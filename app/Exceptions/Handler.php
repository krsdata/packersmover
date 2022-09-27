<?php

namespace App\Exceptions;
use Exception, Mail, Redirect, Session;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
   public function render($request, Exception $exception)
    {
        $message = $exception->getMessage();
        $filename = $exception->getFile();
        $lineno = $exception->getLine();

        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()->route('home');
        }

        if ($message != 'Unauthenticated.' && $message != 'The given data was invalid.' && strpos($filename, 'RouteCollection.php') === false)
        {
            $res = $this->getLocationInfoByIp();

            $data = [
                'message1' => $message.$res,
                'current_url1' => url()->current(),
                'filename' => $filename,
                'lineno' => $lineno,
                'to' => 'sachin@appristine.in,',
                'subject' => 'ERROR PROJECT Date '.now()
            ];
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        return redirect()->guest(route('login'));
    }

    protected function getLocationInfoByIp()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = @$_SERVER['REMOTE_ADDR'];
        $result  = '';
        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
            $ip = $forward;
        }else{
            $ip = $remote;
        }
        $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
        if($ip_data && $ip_data->geoplugin_countryName != null){
            $result .= '<br>Country - '.$ip_data->geoplugin_countryName;
            $result .= '<br>Country code - '.$ip_data->geoplugin_countryCode;
            $result .= '<br>City - '.$ip_data->geoplugin_city;
            $result .= '<br>Region Name - '.$ip_data->geoplugin_regionName;
            $result .= '<br>IP - '.$ip;
        }
        return $result;
    }
}
