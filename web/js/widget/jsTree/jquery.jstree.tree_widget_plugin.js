/* 
 * jsTree Tree Widget 1.0
 * The Tree Widget binding data store. Datastores are build by overriding the `load_node` and `_is_loaded` functions.
 */
(function ($) {
	$.jstree.plugin("tree_widget", {
		defaults : { 
			data : false,
			widget : false,
			correct_state : true,
			progressive_render : false
		},
		_fn : {
			load_node : function (obj, s_call, e_call) { var _this = this; this.load_node_json(obj, function () { _this.__callback({ "obj" : obj }); s_call.call(this); }, e_call); },
			_is_loaded : function (obj) { 
				var s = this._get_settings().tree_widget, d;
				obj = this._get_node(obj);
				if(obj && obj !== -1 && s.progressive_render && !obj.is(".jstree-open, .jstree-leaf") && obj.children("ul").children("li").length === 0 && obj.data("jstreeChildren")) {
					d = this._parse_json(obj.data("jstreeChildren"));
					if(d) {
						obj.append(d);
						$.removeData(obj, "jstreeChildren");
					}
					this.clean_node(obj);
					return true;
				}
				return obj == -1 || !obj || !s.widget || obj.is(".jstree-open, .jstree-leaf") || obj.children("ul").children("li").size() > 0;
			},
			load_node_json : function (obj, s_call, e_call) {
				var s = this.get_settings().tree_widget, d,
					error_func = function () {},
					success_func = function () {};
				obj = this._get_node(obj);
				if(obj && obj !== -1) {
					if(obj.data("jstreeIsLoading")) { return; }
					else { obj.data("jstreeIsLoading",true); }
				}
				switch(!0) {
					case (!s.data && !s.widget): throw "Neither data nor widget settings supplied.";
					case (!!s.data && !s.widget) || (!!s.data && !!s.widget && (!obj || obj === -1)):
						if(!obj || obj == -1) {
							d = this._parse_json(s.data);
							if(d) {
								this.get_container().children("ul").empty().append(d.children());
								this.clean_node();
							}
							else { 
								if(s.correct_state) { this.get_container().children("ul").empty(); }
							}
						}
						if(s_call) { s_call.call(this); }
						break;
					case (!s.data && !!s.widget) || (!!s.data && !!s.widget && obj && obj !== -1):
						success_func = function (d) {
							d = this._parse_json(d);
							if(d) {
								if(obj === -1 || !obj) { this.get_container().children("ul").empty().append(d.children()); }
								else { obj.append(d).children(".jstree-loading").removeClass("jstree-loading"); obj.data("jstreeIsLoading",false); }
								this.clean_node(obj);
								if(s_call) { s_call.call(this); }
							}
							else {
								if(obj === -1 || !obj) {
									if(s.correct_state) { 
										this.get_container().children("ul").empty(); 
										if(s_call) { s_call.call(this); }
									}
								}
								else {
									obj.children(".jstree-loading").removeClass("jstree-loading");
									obj.data("jstreeIsLoading",false);
									if(s.correct_state) { 
										obj.removeClass("jstree-open jstree-closed").addClass("jstree-leaf"); 
										if(s_call) { s_call.call(this); } 
									}
								}
							}
						};
						s.widget.load_item(obj, success_func.bind(this));
						break;
				}
			},
			_parse_json : function (js, is_callback) {
				var d = false, 
					p = this._get_settings(),
					s = p.tree_widget,
					t = p.core.html_titles,
					tmp, i, j, ul1, ul2;

				if(!js) { return d; }
				if($.isFunction(js)) { 
					js = js.call(this);
				}
				if($.isArray(js)) {
					d = $();
					if(!js.length) { return false; }
					for(i = 0, j = js.length; i < j; i++) {
						tmp = this._parse_json(js[i], true);
						if(tmp.length) { d = d.add(tmp); }
					}
				}
				else {
					if(typeof js == "string") { js = { data : js }; }
					if(!js.data && js.data !== "") { return d; }
					d = $("<li>");
					var custom_class = p.tree_widget.widget.settings.custom_class(js.metadata);
					if(custom_class) {
						d.addClass(custom_class);
					}
					if(js.attr) { d.attr(js.attr); }
					if(js.metadata) { d.data("jstree", js.metadata); }
					if(js.state) { d.addClass("jstree-" + js.state); }
					if(!$.isArray(js.data)) { tmp = js.data; js.data = []; js.data.push(tmp); }
					$.each(js.data, function (i, m) {
						tmp = $("<a>");
						if($.isFunction(m)) { m = m.call(this, js); }
						if(typeof m == "string") { tmp.attr('href','#')[ t ? "html" : "text" ](m); }
						else {
							if(!m.attr) { m.attr = {}; }
							if(!m.attr.href) { m.attr.href = '#'; }
							tmp.attr(m.attr)[ t ? "html" : "text" ](m.title);
							if(m.language) { tmp.addClass(m.language); }
						}
						if(!jQuery.isArray(m.icon)) {
							if(m.icon) {
								m.icon = [m.icon];
							} else {
								m.icon = [];
							}
						}
						// var generic_icon_tag = jQuery("<ins>&#160;</ins>").addClass('jstree-icon');
						var icon_tag = jQuery("<ins>&#160;</ins>");
						jQuery.each(m.icon, function(i, icon) {
							var icon_t = icon_tag.clone().addClass("jstree-custom-icon");
							if(icon.indexOf("/") === -1) {
								icon_t.addClass(icon);
							} else {
								icon_t.css("background","url('" + icon + "') center center no-repeat");
							}
							tmp.prepend(icon_t);
						});
						// if(m.icon.length === 0) {
						// 	tmp.prepend(icon_tag);
						// }
						// tmp.prepend(icon_tag.addClass('jstree-icon'));
						if(p.tree_widget.widget.dnd) {
							p.tree_widget.widget.dnd.init_dnd(tmp, p.tree_widget.widget.settings.model_name, p.tree_widget.widget.settings.identifier_from_data(js.metadata), true);
						}
						d.prepend(tmp);
					});
					d.prepend("<ins class='jstree-icon'>&#160;</ins>");
					if(js.children) { 
						if(s.progressive_render && js.state !== "open") {
							d.addClass("jstree-closed").data("jstreeChildren", js.children);
						}
						else {
							if($.isFunction(js.children)) {
								js.children = js.children.call(this, js);
							}
							if($.isArray(js.children) && js.children.length) {
								tmp = this._parse_json(js.children, true);
								if(tmp.length) {
									ul2 = $("<ul>");
									ul2.append(tmp);
									d.append(ul2);
								}
							}
						}
					}
				}
				if(!is_callback) {
					ul1 = $("<ul>");
					ul1.append(d);
					d = ul1;
				}
				return d;
			},
			get_json : function (obj, li_attr, a_attr, is_callback) {
				var result = [], 
					s = this._get_settings(), 
					_this = this,
					tmp1, tmp2, li, a, t, lang;
				obj = this._get_node(obj);
				if(!obj || obj === -1) { obj = this.get_container().find("> ul > li"); }
				li_attr = $.isArray(li_attr) ? li_attr : [ "id", "class" ];
				if(!is_callback && this.data.types) { li_attr.push(s.types.type_attr); }
				a_attr = $.isArray(a_attr) ? a_attr : [ ];

				obj.each(function () {
					li = $(this);
					tmp1 = { data : [] };
					if(li_attr.length) { tmp1.attr = { }; }
					$.each(li_attr, function (i, v) { 
						tmp2 = li.attr(v); 
						if(tmp2 && tmp2.length && tmp2.replace(/jstree[^ ]*|$/ig,'').length) {
							tmp1.attr[v] = tmp2.replace(/jstree[^ ]*|$/ig,''); 
						}
					});
					if(li.hasClass("jstree-open")) { tmp1.state = "open"; }
					if(li.hasClass("jstree-closed")) { tmp1.state = "closed"; }
					a = li.children("a");
					a.each(function () {
						t = $(this);
						if(
							a_attr.length || 
							$.inArray("languages", s.plugins) !== -1 || 
							t.children("ins").get(0).style.backgroundImage.length || 
							(t.children("ins").get(0).className && t.children("ins").get(0).className.replace(/jstree[^ ]*|$/ig,'').length)
						) { 
							lang = false;
							if($.inArray("languages", s.plugins) !== -1 && $.isArray(s.languages) && s.languages.length) {
								$.each(s.languages, function (l, lv) {
									if(t.hasClass(lv)) {
										lang = lv;
										return false;
									}
								});
							}
							tmp2 = { attr : { }, title : _this.get_text(t, lang) }; 
							$.each(a_attr, function (k, z) {
								tmp1.attr[z] = (t.attr(z) || "").replace(/jstree[^ ]*|$/ig,'');
							});
							$.each(s.languages, function (k, z) {
								if(t.hasClass(z)) { tmp2.language = z; return true; }
							});
							if(t.children("ins").get(0).className.replace(/jstree[^ ]*|$/ig,'').replace(/^\s+$/ig,"").length) {
								tmp2.icon = t.children("ins").get(0).className.replace(/jstree[^ ]*|$/ig,'').replace(/^\s+$/ig,"");
							}
							if(t.children("ins").get(0).style.backgroundImage.length) {
								tmp2.icon = t.children("ins").get(0).style.backgroundImage.replace("url(","").replace(")","");
							}
						}
						else {
							tmp2 = _this.get_text(t);
						}
						if(a.length > 1) { tmp1.data.push(tmp2); }
						else { tmp1.data = tmp2; }
					});
					li = li.find("> ul > li");
					if(li.length) { tmp1.children = _this.get_json(li, li_attr, a_attr, true); }
					result.push(tmp1);
				});
				return result;
			}
		}
	});
})(jQuery);
//*/