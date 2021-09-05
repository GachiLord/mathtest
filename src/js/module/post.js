import { async } from 'regenerator-runtime'
import url from './entryUrl'
import $ from 'jquery'
import massage from './massage'


export default async function (controller, action, parameters= JSON.stringify([])){
    try{
        return await $.post( url, { controller: controller, action: action, parameters: parameters } )
    }
    catch{
        massage('Неожиданная ошибка', 'error');
    }
    
}