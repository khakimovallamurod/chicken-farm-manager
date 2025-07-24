<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Registration</title> 
    <link rel="stylesheet" href="../assets/css/login_style.css">
   </head>
<body>
  <div class="wrapper">
    <h2>Registration</h2>
    <form action="add_cilent.php" method="post" id="regform">
      <div class="input-box">
        <input type="text" name="full" placeholder="Enter your fullname" required>
      </div>

      <div class="input-box">
        <input type="text" name="login" placeholder="Enter your username" id="lgn" required>
        <p id="helpblock" style="color:red; font-size: 14px; display: none; padding:none;"></p>

      </div>
      <div class="input-box">
        <input type="tel" name="phone" placeholder="Enter your telafon" required>
      </div>
      <div class="input-box">
        <input type="text" name="email" placeholder="Enter your email" required>
      </div>
      <div class="input-box">
        <input type="password" name="parol" placeholder="Create password" id="pas1" required>
      </div>
      <div class="input-box">
        <input type="password" name="parol2" placeholder="Confirm password" id="pas2" required>
        <p id='mesg' style="font-size:14px; color:red;"></p>
      </div>
      
      <div class="input-box button">
        <button type="submit">Sign up</button>
      </div>
      <div class="text">
        <h3>Already have an account? <a href="login.php">Login now</a></h3>
      </div>
    </form>
  </div>

  <script src="../js/jquery-3.6.0.min.js"></script>
  <script src="../js/sweetalert.min.js"></script>
  <script type="text/javascript">
    $('#lgn').on("keyup", function(){
      let l = $(this).val();
      $.ajax({
        url:"check_username.php",
        method:"POST", 
        data:{
          login:l,
        },
        success:function(data){
          let obj =jQuery.parseJSON(data);
          console.log(obj);
          if(obj.xatolik==0){
            $('#helpblock').css('display', 'block');
            $('#helpblock').html(obj.xabar);
          }else{
            $('#helpblock').css('display', 'none');
          }
        },
        error:function(xhr){
          alert("Internetda muammo bor tekshirib qaytadan urinib ko'ring!");
        }
      })

    });
    $('#regform').submit(function(e){
      e.preventDefault();
      let p1 = $('#pas1').val();
      let p2 = $('#pas2').val();
      if(p1!=p2){
        $('#mesg').html('Parollar mosligini tekshiring?');
        return 0;
      }
      $.ajax({
        url:'add_cilent.php',
        method: "POST",
        data: $('#regform').serialize(),
        success:function(data){
          let obj =jQuery.parseJSON(data);
          console.log(data);
          if(obj.xatolik == 0){
            swal("Good job!", obj.xabar, "success");
            setTimeout(function() {
              window.location.href = "../admin/dasboard.php";
            }, 2000);
          }else{
            swal("Xatolik!", obj.xabar, "error");
          }
        },
        error:function(){
          alert('Xatolik yuz berdi, qaytadan urinib ko\'ring!')
        }
      })
    })
  </script>

</body>
</html>