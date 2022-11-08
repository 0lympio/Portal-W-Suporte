import './bootstrap';
import AlpineI18n from 'alpinejs-i18n';
import Alpine from 'alpinejs';
import { jsPDF } from "jspdf";

Alpine.plugin(AlpineI18n)

window.Alpine = Alpine;
window.jsPDF = jsPDF;
Alpine.start();


$(function() {
    let notes= JSON.parse(sessionStorage.getItem('notes') || '{}');
    $('.widget-button-add').click(function() {
        let esn = $('.sticky-notes');
        let c = esn.find('.sticky-note').length;
        esn.data('stickynotes').add({
            text: 'Nota #' + String(++c)
        });
    });

    $('#widget-area').stickynotes({
        noteWidth: '165px',
        noteHeight: '165px',
        noteRotationRange: 20,
        updateNote: function(note) {
            console.log(notes);
            let noteJson = $(note.closest('.sticky-notes')).data('stickynotes').getJSONObject(note);
            notes[noteJson.text] = noteJson;
            sessionStorage.setItem('notes', JSON.stringify(notes));
            console.log("updateNote()::", noteJson.text);
        },
        deleteNote: function(note) {
            let noteJson = $(note.closest('.sticky-notes')).data('stickynotes').getJSONObject(note);
            delete notes[noteJson.text];
            sessionStorage.setItem('notes', JSON.stringify(notes));

        }
    });

    if(notes){
        Object.entries(notes).forEach((note) => {
            $('.sticky-notes').data('stickynotes').add(note[1]);
        })
    }
});

import './lang';
import './calculator';
import './sticknote';
