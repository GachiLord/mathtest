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
import baseurl from '../baseurl'


export default class LaunchedTest {
    constructor(name, creator,test, date, type, time){
        this.testID = document.location.pathname.split("/").pop();
        if ( localStorage.getItem(this.testID) == undefined ) localStorage.setItem(this.testID, JSON.stringify({ answers:[], switch: 0, time: time}) ); 
        else if ( JSON.parse( localStorage.getItem(this.testID) ).time == undefined ) localStorage.setItem(this.testID, JSON.stringify({ answers:[], switch: 0, time: time}) );

        this.type = type;
        this.creator = creator;
        this.name = name;
        this.test = test;
        this.date = date;
        this.time = time != null ? JSON.parse( localStorage.getItem( this.testID ) ).time : time;
        this.switch = JSON.parse( localStorage.getItem( this.testID ) ).switch;
        this.answers = JSON.parse( localStorage.getItem( this.testID ) ).answers;
    }

    render(){
        //info rendeting
        if ( this.time != undefined && this.type != 'Article' ) $('.info').append('Дата: ' + this.date + '. Название: ' + this.name + '. Автор: ' + this.creator + '. Оставшееся время: ' + this.time + ' мин.');
        else $('.info').append('Дата: ' + this.date + '. Название: ' + this.name + '. Автор: ' + this.creator);
        
        //test rendering
        $('.launchTest').append(this.test[this.switch]);
        //options
        this.load();
        if ( this.type === 'article') $('.launchTest').css('margin-top', 0);
        this.switchTask();
        this.finishtest();
        this.store();
        this.timer();

        //select text in input
        $('.question').on('focus',function(){
            $(this).trigger('select');
        })
        
        
        if ( this.time <= 0 && this.time != null ) {
            $('.launchTest').html('');
            $('.launchTest').append(`<div class="finish-block">Время вышло<div>`);
        }
    }

    finishtest(){

        $('.finish').on('click', async () =>{
            $('.launchTest').html('');
            let name = localStorage.getItem('guestName');
            if ( await post('Info', 'AuthState' ) === 'NotAuthed'){
                if ( name == undefined || name == null || name == 'null' || name == 'undefined' ){
                    localStorage.setItem('guestName', prompt('Введите имя, если хотите, чтобы автор теста знал, кто его прошел. Также вы можете зарегистрироваться для сохранения статистики, редактирования своих тестов.') );
                    name = localStorage.getItem('guestName');
                } 
            }

            $('.launchTest').append(`<div class="finish-block">Твой балл 
            ${await post('Test', 'score', JSON.stringify( {answers: this.answers, publicid: this.testID, name: name} ))} из 100
            </div>`)
        } );
       
    }

    checkAnswer(){
        //ajax
    }

    timer(){
        if ( this.time != undefined && this.type != 'Article' ) setInterval(() => {
            this.time = Number(this.time) - 1; 
            let load = JSON.parse(localStorage.getItem(this.testID));
            load.time = this.time;
            localStorage.setItem(this.testID, JSON.stringify(load));
            $('.info').html('');
            $('.info').append('Дата: ' + this.date + '. Название: ' + this.name + '. Автор: ' + this.creator + '. Оставшееся время: ' + this.time + ' мин.');
            if ( this.time <= 0 ) {
                $('.finish').trigger('click');
            }
        }, 60000);
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
            localStorage.setItem(testID, JSON.stringify({ answers: answersVal, switch: STswitchVal, time: this.time}))
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
            if ( this.type !== 'article' ) $('.launchTest').append(`<button class="openBut previos switch">Предыдущее задание</button><button class="openBut switch finish">Завершить тест</button>`);
        }
        else if ( this.switch === 0 && this.switch + 1 === this.test.length )
        {
            if ( this.type !== 'article' ) $('.launchTest').append(`<button class="openBut switch finish">Завершить тест</button>`);
        }
        else
        {
            $('.launchTest').append(`<button class="openBut previos switch">Предыдущее задание</button><button class="openBut next switch">Следующее задание</button>`);
        }
        $('.next').on('click', () => { $('.launchTest').html(''); $('.info').html(''); this.switch++; this.render() });
        $('.previos').on('click', () => { $('.launchTest').html(''); $('.info').html(''); this.switch--; this.render() });
        
    }
}