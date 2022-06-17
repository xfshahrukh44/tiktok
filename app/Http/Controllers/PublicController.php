<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PublicController extends Controller
{
    public function contact_us(Request $request)
    {
        // dd($request->all());
        $req = $request->all();
        Mail::send([], [], function ($message) use ($req) {
            $message->to('support@kon-app.com')
              ->subject('Nuevo contacto.')
              // or:
              ->setBody('<h1>Nuevo Contacto</h1>Z<h4><strong>Nombre: </strong>'.$req['name'].'</h4>Z<h4><strong>Correo electrónico: </strong>'.$req['email'].'</h4>Z<h4><strong>Asunto: </strong>'.$req['subject'].'</h4>Z<h4><strong>Mensaje: </strong>'.$req['message'].'</h4>', 'text/html'); // for HTML rich messages
          });
        return redirect()->route('contact_us_page');
    }
}
