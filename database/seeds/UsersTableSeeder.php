<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Piso_venta;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $usuario = User::create([
        		'name' => 'usuario-1',
        		'email' => 'usuario1@gmail.com',
        		'password' => bcrypt("12345678")	
        		]);

        $piso_Venta = Piso_venta::create([
        					'nombre' => 'mi puchito 1',
        					'ubicacion' => 'centro cagua',
        					'dinero' => 0,
        					'user_id' => $usuario->id
        					]);


        $usuario = User::create([
        		'name' => 'usuario-2',
        		'email' => 'usuario2@gmail.com',
        		'password' => bcrypt("12345678")	
        		]);

        $piso_Venta = Piso_venta::create([
        					'nombre' => 'mi puchito 2',
        					'ubicacion' => 'la segundera',
        					'dinero' => 0,
        					'user_id' => $usuario->id
        					]);

        $usuario = User::create([
        		'name' => 'usuario-3',
        		'email' => 'usuario3@gmail.com',
        		'password' => bcrypt("12345678")	
        		]);

        $piso_Venta = Piso_venta::create([
        					'nombre' => 'mi puchito 3',
        					'ubicacion' => 'cagua la villa',
        					'dinero' => 0,
        					'user_id' => $usuario->id
        					]);
    }
}
