var HardValidate = {};

debug = function(msg) {
	if (typeof console != "undefined") {
		console.log(msg);
	}
};

HardValidate.css = {
	"warning": "error"
}

HardValidate.validate = function(form) {
	var valid = true;
	// Clean up old messages
	jQuery("." + HardValidate.css.warning).remove();

	for (var qType in HardValidate.isValid) {
		debug(qType);
		jQuery(form).find("." + qType).each(function() {
			debug(this);
			var res = HardValidate.isValid[qType](this);
			if (res !== true && valid) {
				jQuery(this).find('input, textarea').focus();
				valid = false;
			}
			debug(qType + " valid: " + valid + " (" + res + ")");
			HardValidate.messagePool[qType](this, res);
		});
	}
	debug("eh");
	
	return valid;
}

HardValidate.messagePool = {
	"shorttext": function(id, data) {
		if (data === false) {
			jQuery("<div class='"+HardValidate.css.warning+"'>Please fill out this field.</div>").appendTo(jQuery(id));
		}
	},
	"longtext": function(id, data) {
		if (data === false) {
			jQuery("<div class='"+HardValidate.css.warning+"'>Please fill out this field.</div>").appendTo(jQuery(id));
		}
	},
	"multiple": function(id, data) {
		if (data === false) {
			jQuery("<div class='"+HardValidate.css.warning+"'>Please select an option.</div>").appendTo(jQuery(id));
		}
	},
	"gauge": function(id, data) {
		if (data === false) {
			jQuery("<div class='"+HardValidate.css.warning+"'>Please select an option.</div>").appendTo(jQuery(id));
		}
	},
	"preferential": function(id, data) {
		if (data === true) {
			return;
		}

		if (data.indexOf("MAX") > -1) {
			jQuery("<div class='"+HardValidate.css.warning+"'>A preference is beyond the maximum allowed.</div>")
				.appendTo(jQuery(id));
		}
		if (data.indexOf("MIN") > -1) {
			jQuery("<div class='"+HardValidate.css.warning+"'>A preference is beyond the minimum allowed.</div>")
				.appendTo(jQuery(id));
		}
		if (data.indexOf("DUP") > -1) {
			jQuery("<div class='"+HardValidate.css.warning+"'>A preference has a duplicate.</div>")
				.appendTo(jQuery(id));
		}
		if (data.indexOf("NAN") > -1) {
			jQuery("<div class='"+HardValidate.css.warning+"'>A preference contains invalid content.</div>")
				.appendTo(jQuery(id));
		}
		if (data.indexOf("ALL") > -1) {
			jQuery("<div class='"+HardValidate.css.warning+"'>Please fill out all preferences before submitting.</div>")
				.appendTo(jQuery(id));
		}
			
	}
	
};

HardValidate.isValid = {
	"shorttext": function(id) {
		return !HardValidate.isMandatory(id) || jQuery(id).find("input").val().trim() != "";
	},
	"longtext": function(id) {
		return !HardValidate.isMandatory(id) || jQuery(id).find("textarea").val().trim() != "";
	},
	"multiple": function(id) {
		return !HardValidate.isMandatory(id) || jQuery(id).find(":checked(input)").length > 0;
	},
	"gauge": function(id) {
		return !HardValidate.isMandatory(id) || jQuery(id).find(":checked(input)").length > 0;
	},
	"preferential": function(id) {
		var inputs = jQuery(id).find("input"),
			minP = inputs.attr('min'),
			maxP = inputs.attr('max'),
			prefs = [],
			issues = [];

		inputs.each(function() {
			var val;

			if (jQuery(this).val() == "") {
				return;
			}
			
			else if (!/^\d+$/.test(jQuery(this).val())) {
				issues.push("NAN");
				return;
			}
			
			val = parseInt(jQuery(this).val())
			
			if (prefs.indexOf(val) > -1) {
				issues.push("DUP");
			}
			
			else if (val > maxP) {
				issues.push("MAX");
			}
			
			else if (val < minP) {
				issues.push("MIN");
			}
	
			prefs.push(val);
			
		});

		if (maxP != prefs.length) {
			issues.push("ALL");
		}

		if ((HardValidate.isMandatory(id) && issues.length > 0) ||
			(!HardValidate.isMandatory(id) && prefs.length > 0)) {
			return issues;
		} 
		return true;
	}
};

HardValidate.isMandatory = function(id) {
	var res = !!jQuery(id).find('input, textarea').attr('required');
	debug("isMandatory: " + res);
	return res;
};

HardValidate.bindSubmit = function(submit, form) {
	jQuery(submit).click(function(event) {
		if(!HardValidate.validate(form)) {
			event.preventDefault();
		};
	});
	jQuery(submit).submit(function(event) {
		if(!HardValidate.validate(form)) {
			event.preventDefault();
		};
	});
}
