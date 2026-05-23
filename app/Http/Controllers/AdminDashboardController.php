<?php

namespace App\Http\Controllers;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Pagamento;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function admin(){

      return view('admin.dashboard');
    }
}
