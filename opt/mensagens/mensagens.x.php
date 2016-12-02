<?php switch ($_ll['mensagens']){ default: case 'detido': ?>
    
    <div id="permicao">
        <div class="container">
            <h1>Você não tem permissão para acessar está página</h1>
        </div>
    </div>

<?php break; case 'ne_trovi': case 'nao_encontrada': ?>

    <div id="permicao">
        <div class="container">
            <h1>Pagina não encontrada</h1>
        </div>
    </div>

<?php break; }