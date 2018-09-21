<?php

$crear_categoria[0] = <<<'EOT'
    <div class="botones_principales color_back_02" onclick="open_categoria(##id_cae##)">##nombre##</div>
EOT;

$crear_promocion[0] = <<<'EOT'
    <div class="botones_principales color_back_02" onclick="open_promocion(##id_prm##)">##nombre##</div>
EOT;

$html_promos[0] = <<<'EOT'
    <div class='promocion' onclick='open_promocion(##id_prm##)'>
        <div class='nombre'>##nombre##</div>
        <div class='descripcion'>##descripcion##</div>
    </div>
EOT;

$html_categorias[0] = <<<'EOT'
    <div class='categoria' onclick='open_categoria(##id_cae##)'>
        <div class='nombre'>##nombre##</div>
        <div class='descripcion'>##descripcion##</div>
    </div>
EOT;

$html_productos[0] = <<<'EOT'
    <div class='producto' onclick='add_carro_producto(##id_pro##)'>
        <div class='nombre'>##nombre##</div>
        <div class='descripcion'>##descripcion##</div>
    </div>
EOT;



$html['promos'] = $html_promos;
$html['categorias'] = $html_categorias;
$html['productos'] = $html_productos;
$html['crear_categoria'] = $crear_categoria;
$html['crear_promocion'] = $crear_promocion;

?>