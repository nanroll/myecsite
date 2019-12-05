<?php
    if (isset($_SESSION['user_id']) === TRUE) {
        $link = get_db_connect();
        $count = check_cart_count($link, $_SESSION['user_id']);

        close_db_connect($link);
    }
?>
<style>
    body {
        padding-top: 250px;
    }
    
    header {
        background-size: contain;
        background-image: url(https://2.bp.blogspot.com/-zAYLeb_scqo/XLAcyo6Kw2I/AAAAAAABSS8/D3jQCCpE7cUb6ameCj8j2eJ82X8EKS4dgCLcBGAs/s600/bg_himawari_hatake_wide.jpg);
        background-position: center;
        background-repeat: repeat-x;
        color: #000066;
        font-weight: bold;
    }
    
    /*.icon {*/
    /*    float: right;*/
        
    /*}*/
    
    .logo {
        width: 3em;
        height: auto;
    }
    h1 {
        padding-bottom: 15px;
        padding-top: 5px;
    }
    
    @media(max-width: 767px) {
        body {
            padding-top: 160px;
        }
        
        h1 {
            font-size: 1rem;
            /*padding-top: 50px !important;*/
            padding-bottom: 0;
            margin-bottom: 0;
        }
        #header {
            padding-top: 0 !important;
            /*padding-bottom: 1rem !important;*/
            height: 110px 
        }
        .logo {
            margin-top: 0.5rem;
            width: 1.5em;
        }
        .icon {
            margin-top: -50px;
        }
        .fa-layers {
            font-size: 150%;
        }
        
        header .btn {
            font-size: 0.6rem;
        }
    }
    
</style>

<!--<div class="row w-100 justfy-content-bitween">-->
<header class="fixed-top container-fluid pt-3 pr-md-5 pb-md-5 pb-1 pl-md-5" id="header">
    <div class="row">
        <div class="mr-auto col-12 col-md-auto">
            <a href=<?php print 'http://' . SERVERNAME . '/myecsite/nan_coop.html' ?>><img src="https://3.bp.blogspot.com/-mBh7QBCP008/VyNc8Ze6CRI/AAAAAAAA6Jg/du9e6RVAFVsB8abJ_foDA_T9QcsfdHH8gCLcB/s180-c/allergy_cat.png" class="logo"></a>
            <h1><?php print $title ?></h1>
        </div>
        <div class="col-12 col-md-auto">
            <!--TRUEが帰ってきたら要件成立-->
            <!--isset = 変数が定義されているか？-->
            <?php if (is_login() && !isset($header_flg)) { ?>

                <div class="icon d-block d-md-flex text-right">
                    <a href="cart.php" class="d-block d-md-inline">
                    
                    <!--<img src="/PHP/25kadai_ec/cart_on.png" width=50px class="cart">-->
                    <span class="fa-layers fa-fw fa-2x mr-3">     
                        <i class="fas fa-shopping-cart"></i>
                        <span class="fa-layers-counter" style="background:Tomato"><?php print $count ?></span> 
                    </span>
                    </a>
                    <form class="logout d-block d-md-inline" action="logout.php" method="post">
                        <button type="submit" class="btn btn-sm btn-secondary">ログアウト</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
</header>