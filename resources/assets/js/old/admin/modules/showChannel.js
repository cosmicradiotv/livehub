import $ from 'jquery';
import _ from 'lodash';
import Ractive from 'ractive';

import rulesEditorTemplate from '../templates/showChannelRuleEditor.hbs';

var ShowChannel = function (app) {
	this.app = app;
};

ShowChannel.prototype.call = function (method) {
	this.methods[method].apply(this);
};

ShowChannel.prototype.methods = {
	// this = module instance
	'edit': function () {
		$('[data-rules]').each(function () {
			var $container = $('<div>').appendTo($(this).parent()),
				$input = $(this),
				data;

			try {
				data = JSON.parse($(this).val());
			} catch (e) {
				data = [];
			}

			var editor = new RuleEditor({
				el: $container,
				data: {
					rules: data
				}
			});

			// Mirror new data to input (for reload-safety) and on submit
			editor.observe('rules', _.throttle(function () {
				$input.val(JSON.stringify(editor.get('rules')));
			}, 1000));
			$input.parents('form').on('submit', function () {
				$input.val(JSON.stringify(editor.get('rules')));
			});

			$(this).hide();
		});
	}
};

var RuleEditor = Ractive.extend({
	template: rulesEditorTemplate,

	oninit: function () {
		// Adding a rule
		this.on('addRule', function () {
			this.push('rules', {
				'type': null
			});
		});
		// Removing a rule
		this.on('removeRule', function (event, index) {
			this.reordering = true;
			this.splice('rules', index, 1);
			this.reordering = false;
		});
		// On changing rule type clean up
		this.observe('rules.*.type', function (newValue, oldValue, keypath, ruleIndex) {
			if (newValue === null || oldValue === undefined || this.reordering) {
				return;
			}

			this.set('rules.' + ruleIndex, {type: newValue});
		});
	}
});


export default ShowChannel;