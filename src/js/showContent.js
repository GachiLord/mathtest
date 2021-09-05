import post from "./module/post";
import $ from 'jquery';
import main from "./module/main";
import '../assets/css/main.css'
import '../assets/css/profile.css'
import '../assets/css/show.css'
import '../assets/css/persons.css'
import { async } from "regenerator-runtime";




let url = document.location.href;
let baseurl = document.location.host;



$( async () => {
    

    if ( url.includes(`${baseurl}/profile`) ){
        //get profile from server
        $('title').append('Профиль');
        $('.view').addClass('profile');
        $('.profile').prepend( JSON.parse( await post('Selection', 'GetProfile', JSON.stringify( { login: document.location.pathname.split("/").pop() }) ) ) );

        //change profile
        $('.change').on('click', async function(){
            $('body').prepend( await post('Profile', 'edit', JSON.stringify( { login: $('.change').attr('login'), name: $('#name').val(), password: $('.newPass').val(), OldPassword: $('#OldPassword').val() } ) ) );
            location.reload;
            setTimeout(() => {
                $('.error').animate({opacity:0})
                $('.massage').animate({opacity:0})
            }, 2000); 
        });
        
    
        //change role
        $('.changeRole').on('click', async ()=>{
            $('body').prepend( await post('Profile', 'ChangeRole', JSON.stringify( { role: $(':checked').val(), login: $('.changeRole').attr('user') } ) ) );
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
        $('.own').append( JSON.parse ( await post('Selection','GetOwnContent' ) ) );
    }
    else if ( url.includes(`${baseurl}/show`) ){
        let GetParam = (name) => {
            let queryString = location.search;
            let params = new URLSearchParams(queryString);
            return params.get(name);
        }
        

        $('title').append('Тесты');
        $('.view').addClass('own test-view');
        $('.test-view').append( JSON.parse ( await post('Selection','GetContentById', JSON.stringify( { load: 0, page: GetParam('page') }) ) ) );
        if ( $('.content').length < await post('Info', 'GetTestCount') ) $('body').append('<button class="openBut get-more">Ещё тесты</button>');
        $('.get-more').on('click', async function() {
            let href = location.href;
            history.pushState(null,'Тесты', href.replace(GetParam('page'), Number(GetParam('page')) + 20 ) );
            $('.test-view').append( JSON.parse ( await post('Selection','GetContentById', JSON.stringify( { load: $('.content').length, page: GetParam('page') }) ) ) );
            if ( $('.content').length >= await post('Info', 'GetTestCount') ) $(this).detach();
        });
    }
    else if ( url.includes(`${baseurl}/results`) ){
        $('title').append('Результаты');
        $('.view').addClass('person');


        let table = JSON.parse( await post('Selection', 'GetTestStat', JSON.stringify( { testid: document.location.pathname.split("/").pop() } ) ) );
        $('.person').append( "<table border='1' width='100%' cellpadding='5'><tr><th>Имя</th><th>Балл</th></tr></table>");
        
        table.forEach(element => {
            $('table').append(element);
        });
    }
    else if ( url.includes(`${baseurl}/statistic`) ){
        $('title').append('Результаты');
        $('.view').addClass('person');
        let table = JSON.parse( await post('Selection', 'GetOwnStat' ) );
        $('.person').append( "<table border='1' width='100%' cellpadding='5'><tr><th>Тест</th><th>Балл</th></tr></table>");
        
        table.forEach(element => {
            $('table').append(element);
        });
    }


    main();
} );
