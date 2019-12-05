<style>
footer h2 {
    color: #664433;
    /*2倍*/
    line-height: 2;
    padding-left: 30px;
    border-bottom: solid 3px #D3C9C5;
}

footer a {
    text-decoration: none;
    color: #333333;
}

@media (min-width: 768px) {
    /*body {*/
    /*    padding-bottom: 100px;*/
        
    /*}*/
    footer .nav-item {
    border-left: 1px solid #cccccc;
    }
    /*　疑似デレクタ―　a.hover　とか */
    footer .nav-item:first-child {
    border: 0px;
    }
}            

@media (max-width: 767px) {
    body {
        padding-bottom: 200px;
        
    }
    footer {
        font-size: 70%;
    }
    
}            

footer {
    /*最初は表示しない*/
    display: none;
    border-top: solid 1px #CCCCCC;
    background-color: #BBDEFB;
}
</style>
<script>
    $(function() {
        // footerが出現して、（undefにならなければ）footerの高さを取る
        var fh = $('footer').innerHeight();
        if (fh !== undefined) {
            $('body').css('padding-bottom', fh + 'px');
        }
        console.log(fh);
    });
    
    
</script>

<!-- フッター -->
  <footer class="footer fixed-bottom">
    <div class="container-fluid text-center">
      <ul class="nav flex-column flex-md-row justify-content-center mt-2">
        <li class="nav-item"><a href="#" class="nav-link">サイトマップ</a></li>
        <li class="nav-item"><a href="#" class="nav-link">プライバシーポリシー</a></li>
        <li class="nav-item"><a href="#" class="nav-link">お問い合わせ</a></li>
        <li class="nav-item"><a href="#" class="nav-link">ご利用ガイド</a></li>
      </ul>
      <small>Copyright&copy; CodeCamp All Rights Reserved.</small>
    </div>
  </footer>