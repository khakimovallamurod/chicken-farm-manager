<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login</title> 
    <link rel="stylesheet" href="../assets/css/login_style.css">
   </head>
<body>
  <div class="wrapper">
    <h2>Tizimga kirish</h2>
    <form action="tizimga-kirish-tekshir.php" method="post" id="logform">
      <div class="input-box">
        <input type="text" name="login" placeholder="username kiriting" required>
      </div>
      <div class="input-box">
        <input type="password" name="parol" placeholder="parol kiriting" id="pass" required>
      </div>
      <div class="input-box button">
        <button type="submit">Kirish</button>
      </div>
      
    </form>
  </div>
  <script src="../js/jquery-3.6.0.min.js"></script>
  <script src="../js/sweetalert.min.js"></script>
  <script type="text/javascript">
    $('#logform').submit(function(e){
      e.preventDefault();
      $.ajax({
        url:"enter-check.php",
        method:"POST",
        data:$('#logform').serialize(),
        success:function(data){
          let obj =jQuery.parseJSON(data);
          console.log(obj);
          if(obj.xatolik == 0){
            swal("Good job!", obj.xabar, "success");
            if(obj.manzil=='admin'){
              setTimeout(function(){
                window.location.href = '../admin/dashboard.php';
              }, 2000);
            }else{
                setTimeout(function(){
                window.location.href = '../index.php';
              }, 2000);
            }
            
          }
          else{
            $('#pass').val('');
            swal("Xatolik!", obj.xabar, "error");
          }
        },
        error:function(){
          alert("Internetingizda muammo bor qaytadan tekshirib urinib ko'ring!");
        }
      });
    })
  </script>
</body>
</html>