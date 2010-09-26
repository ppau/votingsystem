var tests = {

    //-------------------------------------------------------------------------
	
	'set_test': function (someTestArgs) {
		var deferredResult;
		
		deferredResult = new Clipperz.Async.Deferred("set_test", someTestArgs);
		deferredResult.addCallback(function () {
			var set;
			var	object1;
			var	object2;
			var object3;
	
			set = new Clipperz.Set();

			object1 = new Object();
			object2 = new Object();
			object3 = new Object();
	
			object1.label = "object 1";
			object2.label = "object 2";
			object3.label = "object 3";
	
			is(set.size(), 0, "A new set should be empty");

			set.add(object1);
			is(set.size(), 1);
			is(set.contains(object1), true);
			is(set.contains(object2), false);

			set.add(object1);
			is(set.size(), 1, "Adding the same object twice does not change the set content");
			is(set.contains(object1), true);
			is(set.contains(object2), false);

			set.add(object2);
			is(set.size(), 2);
			is(set.contains(object1), true);
			is(set.contains(object2), true);
			is(set.contains(object3), false);

			set.remove(object1);
			is(set.size(), 1, "Size check after removing an object");
			is(set.contains(object1), false);
			is(set.contains(object2), true);
			is(set.contains(object3), false);

			set.remove(object1);
			is(set.size(), 1, "Removing twice the same object does not change the set content");
			is(set.contains(object1), false);
			is(set.contains(object2), true);
			is(set.contains(object3), false);

			set.empty();
			is(set.size(), 0);
	
			{
				var	items;
				var	populatedSet;
		
				items = ["item1", "item2", "item3"];
		
				populatedSet = new Clipperz.Set({'items': items});
				is(populatedSet.size(), 3);
				is(populatedSet.contains("item1"), true);
				is(populatedSet.contains("item4"), false);
		
				items.splice(0, items.length);
				is(populatedSet.size(), 3);
			}
	
			{
				var	items;
				var	deletedItems;
		
				items = ["item1", "item2", "item3"];
		
				set = new Clipperz.Set({'items': items});
				deletedItems = ["item1"];
				set.remove(deletedItems);
				is(set.size(), 2, "here I am");
				is(set.contains("item1"), false);
				is(set.contains("item2"), true);

				set = new Clipperz.Set({'items': items});
				deletedItems = ["item1", "item2"];
				set.remove(deletedItems);
				is(set.size(), 1);
				is(set.contains("item1"), false);
				is(set.contains("item2"), false);

				set = new Clipperz.Set({'items': items});
				deletedItems = ["item1", "item4"];
				set.remove(deletedItems);
				is(set.size(), 2);
				is(set.contains("item1"), false);
				is(set.contains("item2"), true);
			}

			{
				var items;
				var poppedItem;
		
				items = ["item1", "item2", "item3"];
				set = new Clipperz.Set({'items': items});

				poppedItem = set.popAnItem();
				ok(poppedItem != null, "test popAnItem - 1");
				is(set.size(), 2, "test popAnItem - 2");

				poppedItem = set.popAnItem();
				ok(poppedItem != null, "test popAnItem - 3");
				is(set.size(), 1, "test popAnItem - 4");

				poppedItem = set.popAnItem();
				ok(poppedItem != null, "test popAnItem - 5");
				is(set.size(), 0, "test popAnItem - 6");

				poppedItem = set.popAnItem();
				ok(poppedItem == null, "test popAnItem - 7");
			}
		});
		deferredResult.callback();
		
		return deferredResult;
	},

    //-------------------------------------------------------------------------
    'syntaxFix': MochiKit.Base.noop
};



//#############################################################################

SimpleTest.runDeferredTests("Clipperz.Set", tests, {trace:false});
