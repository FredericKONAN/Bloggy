import { Controller } from '@hotwired/stimulus';
import $ from 'jquery';


export default class extends Controller {
    static values = {
        duration: { type: Number, default: 3 },
        scrollTargetId: String
    };

    connect() {
        setTimeout(() => {
            $(this.element).fadeOut();

            if (this.scrollTargetIdValue !== '') {
                document.getElementById(this.scrollTargetIdValue)
                    .scrollIntoView({ behavior: 'smooth' });
            }
        }, this.durationValue * 1000);
    }
}

