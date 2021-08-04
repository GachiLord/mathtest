import Test from './Test'
import $ from 'jquery'
import getVal from '../getVal'
import checkThem from '../checkboxesChecker'
import render from '../render'
import { async } from 'regenerator-runtime'
import showpersons from '../showPersons'
import getCheckState from '../getCheckState'
import post from '../post'
import '../../../assets/css/finish.css'


export default class LaunchedTest {
    constructor(name, creator,test, date, type){
        this.testID = document.location.pathname.split("/").pop();
        if ( localStorage.getItem(this.testID) == undefined ) localStorage.setItem(this.testID, JSON.stringify({ answers:[], switch: 0}) ); 

        this.type = type;
        this.creator = creator;
        this.name = name;
        this.test = test;
        this.date = date;
        this.switch = JSON.parse( localStorage.getItem( this.testID ) ).switch;
        this.answers = JSON.parse( localStorage.getItem( this.testID ) ).answers;
    }

    render(){
        //info rendeting
        $('.info').append('Дата: ' + this.date + '. Название: ' + this.name + '. Автор: ' + this.creator);
        
        //test rendering
        $('.launchTest').append(this.test[this.switch]);
        //options
        this.load();
        if ( this.type !== 'article') this.switchTask();
        else $('.launchTest').css('margin-top', 0);
        this.finishtest();
        this.store();

        //select text in input
        $('.question').on('focus',function(){
            $(this).trigger('select');
        })        
    }

    finishtest(){

        $('.finish').on('click', async () =>{
            $('.launchTest').html('');
            let name = localStorage.getItem('guestName');
            if ( await post('Info', 'authState' ) === 'notAuthed'){
                if ( name == undefined ){
                    localStorage.setItem('guestName', prompt('Введите имя, чтобы автор теста знал, кто его прошел. Также вы можете зарегистрироваться для сохранения статистики, редактирования своих тестов.') );
                    name = localStorage.getItem('guestName');
                } 
            }

            $('.launchTest').append(`<div class="finish-block">Твой балл 
            ${await post('LaunchedTest', 'finishTest', JSON.stringify( {answers: this.answers, id: this.testID, name: name} ))} из 100
            </div>`)
        } );
       
    }

    checkAnswer(){
        //ajax
    }

    store(){
        let testID = this.testID;
        let switchVal = this.switch;
        let answersVal = this.answers;
        let testLen = this.test.length;
        let STswitchVal = this.switch;
        let input = (userAnswer) => {
            this.answers[this.switch] = userAnswer;
            this.switch = switchVal;
        }
        let store = () => {
            let userAnswer = [];

            //store answers
            $('.question').each( function(){
                if ($(this).is(':checked')) $(this).attr('check','true');
                else $(this).attr('check','false');
                userAnswer.push(getVal($(this)));
            } );
            input(userAnswer);

            //store answers && switch
            localStorage.setItem(testID, JSON.stringify({ answers: answersVal, switch: STswitchVal}))
        }

        

        $('.switch').on('mousedown', function() {
            //checking of switch for storage
            if ( $(this).attr('class').includes('next') && switchVal + 1 <= testLen ) STswitchVal++;
            else if ( $(this).attr('class').includes('previos') && switchVal - 1 >= 0 ) STswitchVal--;

            store();
        });
        $('.question').on('change', store);
    }
    load(){
        //paste users input
        if ( JSON.parse( localStorage.getItem(this.testID) ).answers[this.switch] != undefined ) {
            let answers = this.answers;
            $('.question').each( function(){
                getVal( $(this), answers[ $(this).attr('pageid') ][ $(this).attr('answerid') ] );
                if ( $(this).attr('check') === 'true' ) $(this).trigger('click');
                } );
            }
    }

    switchTask(){
        //switch
        if ( this.switch === 0 && this.switch + 1 !== this.test.length  ){
            $('.launchTest').append(`<button class="openBut next switch">Следующее задание</button>`);
        }
        else if ( this.switch + 1 === this.test.length && this.switch !== 0 )
        {
            $('.launchTest').append(`<button class="openBut previos switch">Предыдущее задание</button><button class="openBut switch finish">Завершить тест</button>`);
        }
        else if ( this.switch === 0 && this.switch + 1 === this.test.length )
        {
            $('.launchTest').append(`<button class="openBut switch finish">Завершить тест</button>`);
        }
        else
        {
            $('.launchTest').append(`<button class="openBut previos switch">Предыдущее задание</button><button class="openBut next switch">Следующее задание</button>`);
        }
        $('.next').on('click', () => { $('.launchTest').html(''); $('.info').html(''); this.switch++; this.render() });
        $('.previos').on('click', () => { $('.launchTest').html(''); $('.info').html(''); this.switch--; this.render() });
        
    }
}