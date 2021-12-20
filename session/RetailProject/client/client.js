$(document).ready(function () {
    /** jQuery.validator.addMethod("passcheck", function(value, element) {
            pattern = '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';
            if (pattern.test(value)) {
                return true;
            } else {
                return false;
            }
        };
    };**/
    $('#form').validate({
      rules: {
        username: {
          required: true
        },
        email: {
          required: true,
          email: true
        },
        contact: {
          required: true,
          rangelength: [11, 11],
          number: true
        },
        password: {
          required: true,
          minlength: 8,
        },
        confirmPassword: {
          required: true,
          equalTo: "#password"
        },
        oldPassword: {
          required: true,
        },
        newPassword: {
          required: true,
          minlength: 8,
        },
        confirmNPassword: {
          required: true,
          equalTo: "#newPassword"
        }

      },
      messages: {
        username: 'Please enter Name.',
        email: {
          required: 'Please enter Email Address.',
          email: 'Enter a valid Email Address.',
        },
        contact: {
          required: 'Please enter Contact.',
          rangelength: 'Enter a valid number.',
        },
        password: {
          required: 'Please enter Password.',
          minlength: 'Must be at least 8 characters.',          
        },
        confirmPassword: {
          required: 'Please enter Confirm Password.',
          equalTo: "Passwords don't match.",
        },
        oldPassword: {
          required: 'Please enter old Password.',         
        },
        newPassword: {
          required: 'Please enter new Password.',    
          minlength: 'Must be at least 8 characters.',     
        },
        confirmNPassword: {
          required: 'Please enter Confirm Password.',
          equalTo: "Passwords don't match.",
        }
      },
      submitHandler: function (form) {
        form.submit();
      }
    });

  });

  function toggle(userPass) {
    let password = document.getElementById(userPass);
    let eye = document.getElementById("toggle");

    if (password.getAttribute("type")=="password") {
        password.setAttribute("type","text");
    } else {
        password.setAttribute("type", "password");
    }
  }