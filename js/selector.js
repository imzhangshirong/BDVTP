$.initSelector=function(){
    var selectors=$(".selector");
    for(var a=0;a<selectors.length;a++){
        var tablist=$(selectors[a]).children(".tablist");
        var tabs=$(selectors[a]).children(".tab");
        var tabs_s=$(selectors[a]).children(".tab.selected");
        if(tablist.length>0){
            var tabname=$(tablist[0]).children("span");
            if(tabname.length!=tabs.length){
                var id=(tabs_s.length>0)?tabs_s.index():0;
                tablist.remove();
                $(selectors[a]).prepend('<div class="tablist"></div');
                tablist=$(selectors[a]).children(".tablist");
                for(var b=0;b<tabs.length;b++){
                    tablist.append('<span>'+$(tabs[b]).attr("name")+'</span>');
                }
                tablist.children("span:nth("+id+")").addClass("selected");
                tablist.children("span").on("click",function(){
                    var id=$(this).index();
                    var selector=$(this).parent(".tablist").parent(".selector");
                    selector.find(".tablist span").removeClass("selected");
                    selector.children(".tab").removeClass("selected");
                    $(this).addClass("selected");
                    selector.children(".tab:nth("+id+")").addClass("selected");
                });
            }
        }
        else{
            $(selectors[a]).prepend('<div class="tablist"></div');
            tablist=$(selectors[a]).children(".tablist");
            for(var b=0;b<tabs.length;b++){
                tablist.append('<span>'+$(tabs[b]).attr("name")+'</span>');
            }
            tablist.children("span:nth(0)").addClass("selected");
            tablist.children("span").on("click",function(){
                var id=$(this).index();
                var selector=$(this).parent(".tablist").parent(".selector");
                selector.find(".tablist span").removeClass("selected");
                selector.children(".tab").removeClass("selected");
                $(this).addClass("selected");
                selector.children(".tab:nth("+id+")").addClass("selected");
            });

        }
    }
}