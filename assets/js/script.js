$(function(){ // Equivalent au window.onload

    $('#content').load('templates/feed.php')
    $('#menu').load('menu_pc.php')
    $('#notif').load('templates/notif_refresh.php')
    $('#notif_2').load('templates/notif_refresh.php')
    $('#notification_test_b').load('templates/notif.php')


    $("#upload").click(function () {
        $("input[type='file']").trigger('click');
      });
      
      $('input[type="file"]').on('change', function() {
        var val = $(this).val();
        $(this).siblings('span').text(val);
      })

    // myfeed = setInterval(function () {
    //     $('#content').load('templates/feed.php')
    // }, 5000);

   mynotifr=  setInterval(function () {
        $('#notif').load('templates/notif_refresh.php')
        $('#notif_2').load('templates/notif_refresh.php')
    }, 2000);

    mynotif = setInterval(function () {
        $('#notification_test_b').load('templates/notif.php')
    }, 2000);

    $("#refresh").click(function () {
        $('#content').load('templates/feed.php')
        $('#notif').load('templates/notif_refresh.php')
        $('#notif_2').load('templates/notif_refresh.php')
    });

// Form Ajax

$("#buttonMenu").click(function() {
    $('#haut-profil').hide();
    $('#profil-picture').hide();
    $('#buttonMenu').hide();
    $('#collapseExample').collapse('show')
    $('#closeMenu').show();
    
  });

  $("#closeMenu").click(function() {
    $('#haut-profil').show();
    $('#profil-picture').show();
    $('#buttonMenu').show();
    $('#collapseExample').collapse('hide')

    $('#closeMenu').hide();
  });
  

  $('.search-box input[type="text"]').on("keyup input", function(){
    /* Get input value on change */
    var inputVal = $(this).val();
    var resultDropdown = $(this).siblings(".result");
    if(inputVal.length){
        $.get("search_result.php", {term: inputVal}).done(function(data){
            // Display the returned data in browser
            resultDropdown.html(data);
        });
    } else{
        resultDropdown.empty();
    }
});

// Set search input value on click of result item
$(document).on("click", ".result p", function(){
    $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
    $(this).parent(".result").empty();
});

}); // Fin de ready

function stopActu(){
    clearTimeout(myfeed);
    }

    function stopNotifR(){
        clearTimeout(mynotifr);
    }

    function stopNotif(){
        clearTimeout(mynotif);
        }

function reprise(){
    setInterval(function () {
    $('#content').load('templates/feed.php')
}, 60000);

setInterval(function () {
    $('#content').load('templates/feed.php')
}, 60000);

setInterval(function () {
    $('#notif').load('templates/notif_refresh.php')
    $('#notif_2').load('templates/notif_refresh.php')
}, 2000);

setInterval(function () {
    $('#notification_test_b').load('templates/notif.php')
}, 2000);
}



function readURL(input) {
    $('#blah').show();
           if (input.files && input.files[0]) {
               var reader = new FileReader();

               reader.onload = function (e) {
                   $('#blah')
                       .attr('src', e.target.result)
                       .width(150)
                       .height(150);
               };

               reader.readAsDataURL(input.files[0]);
           }
}