import post from "./module/post";
import $ from 'jquery';
import main from "./module/main";
import '../assets/css/main.css'
import { async } from "regenerator-runtime";
import '../assets/css/profile.css'
import '../assets/css/show.css'
import '../assets/css/persons.css'



let url = document.location.href;
let baseurl = document.location.host;



$( async () => {
    if ( url.includes(`${baseurl}/profile`) ){
        //get profile from server
        $('title').append('Профиль');
        $('.view').addClass('profile');
        $('.profile').prepend( JSON.parse( await post('Get', 'getProfileByLogin', JSON.stringify( { login: document.location.pathname.split("/").pop() }) ) ) );

        //change pass
        $('.changePass').on('click', async ()=>{
            $('body').prepend( await post('Profile', 'changePass', JSON.stringify( { password: $('.password').val(), newPass: $('.newPass').val() } ) ) );
            setTimeout(() => {
                $('.error').animate({opacity:0})
                $('.massage').animate({opacity:0})
            }, 2000); 
        });
    
        //change role
        $('.changeRole').on('click', async ()=>{
            $('body').prepend( await post('Profile', 'changeRole', JSON.stringify( { role: $(':checked').val(), id: $('.changeRole').attr('user') } ) ) );
            setTimeout(() => {
                $('.error').animate({opacity:0})
                $('.massage').animate({opacity:0})
            }, 2000); 
        });
        //change name
        $('.changeName').on('click', async ()=>{
            $('body').prepend( await post('Profile', 'changeName', JSON.stringify( { name: $('#name').val() } ) ) );
            setTimeout(() => {
                $('.error').animate({opacity:0})
                $('.massage').animate({opacity:0})
            }, 2000); 
        });
    }
    else if ( url.includes(`${baseurl}/my`) ){
        $('title').append('Мои тесты');
        $('.view').addClass('own test-view');

        //test view
        $('.own').append( JSON.parse ( await post('Get','getOwnContent' ) ) );
    }
    else if ( url.includes(`${baseurl}/show`) ){
        $('title').append('Тесты');
        $('.view').addClass('own test-view');
        $('.test-view').append( JSON.parse ( await post('Get','getContentAcPublicId', JSON.stringify( {id:[100]}) ) ) );
    }
    else if ( url.includes(`${baseurl}/results`) ){
        $('title').append('Результаты');
        $('.view').addClass('person');


        let table = JSON.parse( await post('Get', 'getTestStats', JSON.stringify( { id: document.location.pathname.split("/").pop() } ) ) );
        $('.person').append( "<table border='1' width='100%' cellpadding='5'><tr><th>Имя</th><th>Балл</th></tr></table>");
        
        table.forEach(element => {
            $('table').append(element);
        });
    }
    else if ( url.includes(`${baseurl}/statistic`) ){
        $('title').append('Результаты');
        $('.view').addClass('person');

        let table = JSON.parse( await post('Get', 'getOwnStats' ) );
        $('.person').append( "<table border='1' width='100%' cellpadding='5'><tr><th>Тест</th><th>Балл</th></tr></table>");
        
        table.forEach(element => {
            $('table').append(element);
        });
    }


    main();
} );
