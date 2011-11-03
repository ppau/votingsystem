var HardValidate = {};

debug = function(msg) {
	if (typeof console != "undefined") {
		console.log(msg);
	}
};

HardValidate.css = {
	"warning": "warning"
}

HardValidate.validate = function(form) {
	var valid = true;
	// Clean up old messages
	$("." + HardValidate.css.warning).remove();

	for (var qType in HardValidate.isValid) {
		debug(qType);
		$(form).find("." + qType).each(function() {
			debug(this);
			var res = HardValidate.isValid[qType](this);
			if (res !== true && valid) {
				$(this).find('input, textarea').focus();
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
			$("<div class='"+HardValidate.css.warning+"'>Please fill out this field.</div>").appendTo($(id));
		}
	},
	"longtext": function(id, data) {
		if (data === false) {
			$("<div class='"+HardValidate.css.warning+"'>Please fill out this field.</div>").appendTo($(id));
		}
	},
	"multiple": function(id, data) {
		if (data === false) {
			$("<div class='"+HardValidate.css.warning+"'>Please select an option.</div>").appendTo($(id));
		}
	},
	"gauge": function(id, data) {
		if (data === false) {
			$("<div class='"+HardValidate.css.warning+"'>Please select an option.</div>").appendTo($(id));
		}
	},
	"preferential": function(id, data) {
		if (data === true) {
			return;
		}

		if (data.indexOf("MAX") > -1) {
			$("<div class='"+HardValidate.css.warning+"'>A preference is beyond the maximum allowed.</div>")
				.appendTo($(id));
		}
		if (data.indexOf("MIN") > -1) {
			$("<div class='"+HardValidate.css.warning+"'>A preference is beyond the minimum allowed.</div>")
				.appendTo($(id));
		}
		if (data.indexOf("DUP") > -1) {
			$("<div class='"+HardValidate.css.warning+"'>A preference has a duplicate.</div>")
				.appendTo($(id));
		}
		if (data.indexOf("NAN") > -1) {
			$("<div class='"+HardValidate.css.warning+"'>A preference contains invalid content.</div>")
				.appendTo($(id));
		}
		if (data.indexOf("ALL") > -1) {
			$("<div class='"+HardValidate.css.warning+"'>Please fill out all preferences before submitting.</div>")
				.appendTo($(id));
		}
			
	}
	
};

HardValidate.isValid = {
	"shorttext": function(id) {
		return !HardValidate.isMandatory(id) || $(id).find("input").val().trim() != "";
	},
	"longtext": function(id) {
		return !HardValidate.isMandatory(id) || $(id).find("textarea").val().trim() != "";
	},
	"multiple": function(id) {
		return !HardValidate.isMandatory(id) || $(id).find(":checked(input)").length > 0;
	},
	"gauge": function(id) {
		return !HardValidate.isMandatory(id) || $(id).find(":checked(input)").length > 0;
	},
	"preferential": function(id) {
		var inputs = $(id).find("input"),
			minP = inputs.attr('min'),
			maxP = inputs.attr('max'),
			prefs = [],
			issues = [];

		inputs.each(function() {
			var val;

			if ($(this).val() == "") {
				return;
			}
			
			else if (!/^\d+$/.test($(this).val())) {
				issues.push("NAN");
				return;
			}
			
			val = parseInt($(this).val())
			
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
	var res = !!$(id).find('input, textarea').attr('required');
	debug("isMandatory: " + res);
	return res;
};

HardValidate.bindSubmit = function(submit, form) {
	$(submit).click(function(event) {
		if(!HardValidate.validate(form)) {
			event.preventDefault();
		};
	});
	$(submit).submit(function(event) {
		if(!HardValidate.validate(form)) {
			event.preventDefault();
		};
	});
}
