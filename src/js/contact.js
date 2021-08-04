import $ from 'jquery'
import '../assets/css/main.css'
import mail from '../assets/img/mail.svg'
import phone from '../assets/img/phone.svg'
import main from './module/main'
import post from './module/post'





$( ()=>{
    $('.mail').prepend(`<img src="${mail}" width="40px">`);
    $('.phone').prepend(`<img src="${phone}" width="40px">`);



    main();
} );