jQuery(document).ready(function($){
    Validator.useLang('fa');

    var allFormsData={};
    var login_form=$('#tnd_login_form');
    var username = $('#username');
    var password = $('#password');
    var login_btn = $('#login_submit');


    password.keydown(function (e) {
        if (e.keyCode == 13) {
            login_btn.trigger('click');
            e.stopPropagation();
            return false;
        }
    });

    login_btn.on('click',function (event) {
        login_form.find(".login-username-error").html("").addClass("hidden");
        login_form.find(".login-password-error").html("").addClass("hidden");

        allFormsData.username = username.attr('value');
        if (undefined === allFormsData.username)
        {
            allFormsData.username = username.val();
        }

        allFormsData.password = password.attr('value');
        if (undefined === allFormsData.password)
        {
            allFormsData.password = password.val();
        }

        let loginData = {
            username: allFormsData.username,
            password: allFormsData.password
        };

        let rules = {
            username: 'required',
            password: 'required'
        };

        let validation = new Validator(loginData, rules);


        if (validation.fails())
        {
            var username_validation_errors = validation.errors.get('username');
            if (username_validation_errors && username_validation_errors.length > 0)
            {
                login_form.find(".login-username-error").html(validation.errors.errors['username'][0]).removeClass("hidden");
            }

            var password_validation_errors = validation.errors.get('password');
            if (password_validation_errors && password_validation_errors.length > 0)
            {
                login_form.find(".login-password-error").html(validation.errors.errors['password'][0]).removeClass("hidden");
            }

        }
        else
        {
            login_form.find(".login-username-error").html("").addClass("hidden");
            login_form.find(".login-password-error").html("").addClass("hidden");
            login_btn.find("i").addClass(' icon-unlock2 fa fa-circle-o-notch fa-spin');
            login_btn.prop('disabled', true);


            $.post(
                myData.ajax_url,
                {
                    action : 'tnd_login',
                    username: allFormsData.username,
                    password: allFormsData.password
                },
                function (response)
                {
                  if (response.status === "true")
                  {
                      if(response.user_type === "customer")
                      {
                          window.location.href = myData.home_url+'/profile';
                      }
                      if(response.user_type === "customer")
                      {
                          window.location.href = myData.home_url+'/profile';
                      }
                      if(response.user_type === "shop")
                      {
                          window.location.href = myData.home_url+'/shop_profile';
                      }
                      else
                      {
                          login_btn.find("i").removeClass('fa fa-circle-o-notch fa-spin');
                          login_btn.prop('disabled', false);
                          $.notify(
                              {
                                  message: 'کاربری شما توسط مدیر غیر فعال شده است!'
                              },
                              {
                                  type: 'danger',
                                  placement: {
                                      from: "top",
                                      align: "right"
                                  },
                                  showProgressbar: true,
                                  mouse_over: "pause",
                                  allow_dismiss: true,
                                  animate: {
                                      enter: 'animated slideInDown',
                                      exit: 'animated slideOutUp'

                                  }
                              }
                          );
                      }

                  }
                  else
                  {
                      login_btn.find("i").removeClass('fa fa-circle-o-notch fa-spin');
                      login_btn.prop('disabled', false);
                      $.notify(
                          {
                              message: 'نام کاربری یا گذر واژه نادرست است!'
                          },
                          {
                              type: 'danger',
                              placement: {
                                  from: "top",
                                  align: "right"
                              },
                              showProgressbar: true,
                              mouse_over: "pause",
                              allow_dismiss: true,
                              animate: {
                                  enter: 'animated slideInDown',
                                  exit: 'animated slideOutUp'

                              }
                          }
                      );
                  }
                });
        }

    });


});