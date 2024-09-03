<?php

namespace App\Menu;

use Illuminate\Support\Facades\DB;

class MenuManager
{
    public function __construct() {}

    /**
     * Obtiene los datos del menú para un usuario específico.
     *
     * @param int $idUsuario
     * @return \Illuminate\Support\Collection
     */
    public function getMenuData($idUsuario)
    {
        $query = DB::table('user_views')->where('id_usuario', $idUsuario);
        // dd($query->toSql(), $query->getBindings()); // Esto mostrará la consulta SQL y los valores vinculados
        return $query->get();
    }


    /**
     * Genera el menú dinámico basado en los datos del usuario.
     *
     * @param \Illuminate\Support\Collection $data
     * @return string
     */
    public function getMenu($data)
    {
        $cadena = '';
        $menuPrincipal = [];
        $username = ''; // Variable para almacenar el nombre del usuario

        // Agrupamos los menús por su id_menu_principal
        foreach ($data as $value) {
            // Capturamos el nombre de usuario (será el mismo en cada iteración)
            if (empty($username)) {
                $username = $value->username;
            }

            if (!isset($menuPrincipal[$value->id_menu_principal])) {
                $menuPrincipal[$value->id_menu_principal] = [
                    'nombre' => $value->nombre_menu_principal,
                    'submenus' => []
                ];
            }

            // Agregamos los submenús a la lista
            $menuPrincipal[$value->id_menu_principal]['submenus'][] = [
                'nombre' => $value->nombre_menu,
                'url' => $value->directorio,
            ];
        }

        // Generamos el HTML del menú
        foreach ($menuPrincipal as $principal) {
            $cadena .= '<li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <h4 style="color: white">
                                - ' . $principal['nombre'] . ':
                                <i class="right fas fa-angle-left"></i>
                            </h4>
                        </a>
                        <ul>';

            foreach ($principal['submenus'] as $submenu) {
                $cadena .= '<li class="menu-item active">
                            
                            <a href="' . $submenu['url'] . '" class="menu-link">
                                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                                <div>' . $submenu['nombre'] . '</div>
                            
                            </a>
                        </li>';
            }

            $cadena .= '</ul>
                    </li>';
        }

        // Almacenamos tanto el menú como el nombre de usuario en la sesión
        session([
            'menu' => $cadena,
            'username' => $username,
        ]);

        return $cadena;
    }
}
