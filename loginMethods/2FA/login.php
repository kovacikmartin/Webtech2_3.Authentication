<!DOCTYPE html>
<html>
<head>
    <title>Login</title>     
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" type="text/javascript"></script>
</head>
<body>
    <div id="loginstatus">Not logged in</div>
    <div id="loginform">
        Code: <input type="text" id="googlecode" />
        <input type="submit" id="submit-googlecode" value="Submit" />
    </div>
    
   <script>
     $('input#submit-googlecode').on('click', function() {
      var googlecode = $('input#googlecode').val();
      if ($.trim(googlecode) != '') {
          $.post('check.php', {code: googlecode}, function(data) {
              $('div#loginstatus').text(data);
              if (data == 1) {
                  $('div#loginstatus').text('Logged in');
                  $('div#loginform').hide();
              }
          });
      }
     });
   </script>
   
</body>
</html>