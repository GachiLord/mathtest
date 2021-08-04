import show from './module/show'
import $ from 'jquery'
import '../assets/css/main.css'
import './module/show'
import '../assets/css/show.css'
import { async } from 'regenerator-runtime'
import post from './module/post'
import main from './module/main'





$(async () => {
        
        //test view
        $('.test-view').append( JSON.parse ( await post('Get','getContentAcPublicId', JSON.stringify( {id:[100]}) ) ) );
        main();
} );
