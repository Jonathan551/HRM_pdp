<?php
use yii\helpers\Html;
?>
<nav class="navbar navbar-transparent navbar-expand-lg navbar-absolute fixed-top" role="navigation-demo">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <a class="navbar-brand" href="#"><?=$this->title;?></a>
        </div>

        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">person</i>
                        <p class="d-lg-none d-md-block">
                            Account
                        </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                        <?=
                            Yii::$app->user->isGuest ? 
                                Html::a(
                                    'Log in',
                                    ['/site/login'],
                                    ['class'=>'dropdown-item']
                                ) : 
                                Html::a(
                                    'Log out ('.Yii::$app->user->identity->username.')',
                                    ['/site/logout'],
                                    ['class'=>'dropdown-item','data-method'=>'post']
                                );
                        ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <!-- /.container-->
</nav>