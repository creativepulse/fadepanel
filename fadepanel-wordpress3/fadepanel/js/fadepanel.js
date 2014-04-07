
/**
 * Fade Panel
 *
 * @version 1.2
 * @author Creative Pulse
 * @copyright Creative Pulse 2013-2014
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link http://www.creativepulse.gr
 */


function CpWdgJs_FadePanel_init() {
	for (var i_db = 0, len_db = document["CpWdgJs_FadePanel_db"].length; i_db < len_db; i_db++) {

		(function (params) {

			this.iname = params.iname;
			this.interval = params.interval;
			this.step = params.step;
			this.href = params.href;
			this.hpp = params.hpp;
			this.progress = 0;
			this.state = 0;

			var _this = this;

			this.wdg1 = document.getElementById(params.wdg1_id);
			if (this.wdg1 && params.wdg1_move_id != "") {
				var srcwdg = document.getElementById(params.wdg1_move_id);
				if (srcwdg) {
					srcwdg.style.display = "block";
					this.wdg1.appendChild(srcwdg);
				}
			}

			this.wdg2 = document.getElementById(params.wdg2_id);
			if (this.wdg2) {
				if (params.wdg2_move_id != "") {
					var srcwdg = document.getElementById(params.wdg2_move_id);
					if (srcwdg) {
						srcwdg.style.display = "block";
						this.wdg2.appendChild(srcwdg);
					}
				}

				this.wdg2.onmouseover = function() { _this.h_mouseover(); }
				this.wdg2.onmouseout = function() { _this.h_mouseout(); }
				this.wdg2.style.opacity = 0.01;
				this.wdg2.style.filter = "alpha(opacity=1)";
				this.wdg2.style.display = "block";

				if (this.href != "") {
					this.wdg2.style.cursor = "pointer";
					this.wdg2.onclick = function() { window.location = _this.href; }
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
					this.wdg1.style.filter = "alpha(opacity=" + opacity + ")";
				}

				var opacity = this.progress;
				if (opacity < 1) {
					opacity = 1;
				}

				this.wdg2.style.opacity = opacity / 100;
				this.wdg2.style.filter = "alpha(opacity=" + opacity + ")";

				if (this.state == -1 || this.state == 2) {
					var _this = this;
					setTimeout(function () { _this.h_timer(); }, this.interval);
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

		})(document["CpWdgJs_FadePanel_db"][i_db]);

	}
}

if (window.addEventListener) {
	window.addEventListener("load", CpWdgJs_FadePanel_init, false);
}
else if (window.attachEvent) {
	window.attachEvent("onload", CpWdgJs_FadePanel_init);
}
