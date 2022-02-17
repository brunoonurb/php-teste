
<?php

$variavel = 10;
$variavel2 = 100;
$variavel3 = 1000;

?>
 <form id="formLogin" class="m-t" role="form" action="segurancaValida.php" method="post">
                <div class="form-group">
                    <input type="email" name="email" class="form-control rounded-sm" placeholder="Usuário" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="senha" class="form-control rounded-sm" placeholder="Senha" required="">
                </div>
                <button type="button" id="entrar" class="btn btn-black rounded-sm block full-width m-b ladda-button" data-style="zoom-out">Acessar o sistema</button>

                <a href="#"><small>Esqueceu sua senha?</small></a>
            </form>