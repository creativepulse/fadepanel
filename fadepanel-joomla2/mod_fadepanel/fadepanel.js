
/**
 * fadepanel
 *
 * @version 1.1
 * @author Creative Pulse
 * @copyright Creative Pulse 2009-2013
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link http://www.creativepulse.gr
 */

function Fadepanel(iname, wdg1_id, wdg1_move_id, wdg2_id, wdg2_move_id, interval, step, href, hpp) {
    this.iname = iname;
    this.interval = interval;
    this.step = step;
    this.href = href;
    this.hpp = hpp;
    this.progress = 0;
    this.state = 0;

    this.wdg1 = document.getElementById(wdg1_id);
    if (this.wdg1 && wdg1_move_id != '') {
        var srcwdg = document.getElementById(wdg1_move_id);
        if (srcwdg) {
            srcwdg.style.display = 'block';
            this.wdg1.appendChild(srcwdg);
        }
    }

    this.wdg2 = document.getElementById(wdg2_id);
    if (this.wdg2) {
        if (wdg2_move_id != '') {
            var srcwdg = document.getElementById(wdg2_move_id);
            if (srcwdg) {
                srcwdg.style.display = 'block';
                this.wdg2.appendChild(srcwdg);
            }
        }

        this.wdg2.setAttribute('iname', iname);
        this.wdg2.onmouseover = function() { document[this.getAttribute('iname')].h_mouseover(); }
        this.wdg2.onmouseout = function() { document[this.getAttribute('iname')].h_mouseout(); }
        this.wdg2.style.opacity = 0.01;
        this.wdg2.style.filter = 'alpha(opacity=1)';
        this.wdg2.style.display = 'block';

        if (this.href != '') {
            this.wdg2.style.cursor = 'pointer';
            this.wdg2.onclick = function() { window.location = document[this.getAttribute('iname')].href; }
        }
    }

    this.h_timer = function () {
        if (this.state == 2) {
            this.progress += this.step;
            if (this.progress > 100) {
                this.progress = 100;
                this.state = 1;
            }
        }
        else if (this.state == -1) {
            this.progress -= this.step;
            if (this.progress < 0) {
                this.progress = 0;
                this.state = 0;
            }
        }

        if (this.hpp) {
            var opacity = 100 - this.progress;
            if (opacity < 1) {
                opacity = 1;
            }
        
            this.wdg1.style.opacity = opacity / 100;
            this.wdg1.style.filter = 'alpha(opacity=' + opacity + ')';
        }

        var opacity = this.progress;
        if (opacity < 1) {
            opacity = 1;
        }

        this.wdg2.style.opacity = opacity / 100;
        this.wdg2.style.filter = 'alpha(opacity=' + opacity + ')';

        if (this.state == -1 || this.state == 2) {
            setTimeout('document["' + this.iname + '"].h_timer()', this.interval);
        }
    }

    this.h_mouseover = function () {
        this.state = 2;
        this.h_timer();
    }

    this.h_mouseout = function () {
        this.state = -1;
        this.h_timer();
    }

}
