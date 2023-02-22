<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function login(Request $req)
    {
        $email=$req->email;
        $pass=$req->password;

        //verificar si los campos estan vacios
        if (empty($email) OR empty($pass)) {
            return response()->json([
                'status'=>"error",
                'message'=>"tienes que llenar ambos campos",
            ]);
        }

        $client=new Client();
        try {
            /*$client->request('POST','http://shoppers-serv.test/v1/oauth/token',[
                "form_params"=>[
                    "client_secret"=>"ttyqRUq09VLdB5ofNsk3usHtOIqEPL4Bfog4eVRq",
                    "grant_type"=>"password",
                    "client_id"=>2,
                    "username"=>$req->email,
                    "password"=>$req->password
                ]
                ]);*/
            /*return $client->post('http://shoppers-serv.test/v1/oauth/token',[
                "form_params"=>[
                    "client_secret"=>"ttyqRUq09VLdB5ofNsk3usHtOIqEPL4Bfog4eVRq",
                    "grant_type"=>"password",
                    "client_id"=>2,
                    "username"=>$req->email,
                    "password"=>$req->password
                ]
            ]);*/
            /*return response()->json([
                'email'=>$email,
                'password'=>$pass,
            ]);*/

            $res=(new \GuzzleHttp\Client())->post(
                config('service.passport.login_endpoint'),
                [
                    'headers'=>[
                        'Autorization'=>'Basic '.'token=='
                    ],
                    'form_params'=>[
                        "client_secret"=>config('service.passport.client_secret'),
                        "grant_type"=>"password",
                        "client_id"=>config('service.passport.client_id'),
                        "username"=>$req->email,
                        "password"=>$req->password
                    ],
                ]
                );
            return $res->getBody()->getContents();
        } catch (BadResponseException $e) {
            return response()->json([
                'status'=>'error',
                'message'=>$e->getMessage()
            ]);
        }

    }

    public function logout(Request $req)
    {
        try {
            auth()->user()->tokens()->each(function ($token)
            {
                $token->delete();
            });

            return response()->json([
                'status'=>'success',
                'message'=>'sesion finalizada extosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'=>'error',
                'message'=>$e->getMessage()
            ]);
        }
    }

    public function register(Request $req)
    {
        $name=$req->name;
        $email=$req->email;
        $pass=$req->password;

        //checar si los campos estan vacios
        if (empty($name) or empty($email) or empty($pass)) {
            return response()->json([
                $data=[
                    'status'=>'error',
                    'message'=>'Debes llenar todos los campos'
                ],
                $status=401
            ]);
        }
        //checar si el email es valido
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                $data=[
                    'status'=>'error',
                    'message'=>'Debes introducir un email valido'
                ],
                $status=401
            ]);
        }
        //checar si la contra tiene mas de 5  caracteres
        if (strlen($pass)<6) {
            return response()->json([
                $data=[
                    'status'=>'error',
                    'message'=>'La password debe tener minimi 6 caracteres'
                ],
                $status=401
            ]);
        }
        //checar si el usuario ya existe
        if (User::where('email',$email)->exists()) {
            return response()->json([
                $data=[
                    'status'=>'error',
                    'message'=>'El usuario ya existe'
                ],
                $status=401
            ]);
        }

        //creando nuevo usuario
        try {
            $user=new User();
            $user->name=$name;
            $user->email=$email;
            $user->password=app('hash')->make($pass);

            if ($user->save()) {
                //llamaremos el metood del login
                //return 'usuario creado exitosamente';
                return $this->login($req);
            }
        } catch (\Exception $e) {
            return response()->json([
                $data=[
                    'status'=>'error',
                    'message'=>$e->getMessage()
                ],
            ]);
        }
    }
}
