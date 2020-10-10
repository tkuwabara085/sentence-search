var sectionlist=[];
for(var i=1;i<18;i++){
    sectionlist.push(String(i))
}
var sectionadd1=['18','19'];
var sectionadd2=['20','21'];
var sectioncom=['0'];
var vm=new Vue({
    el:"#wrap",
    data:{
        sectionlist:sectionlist,
        sectionadd1:sectionadd1,
        sectionadd2:sectionadd2,
        sectioncom:sectioncom,
        show:false,
        showall:false,
        grade:0,
        sections:[],
    },
    computed:{
        unchecked:function(){
            if(this.grade==0||this.sections.length==0){
                return true
            }else{
                return false
            }
        },
        correspond1:function(){
            if(this.grade>0){
                return false
            }else{
                return true
            }
        },
        correspond2:function(){
            if(this.grade>=2){
                return false
            }else{
                return true
            }
        },
        correspond3:function(){
            if(this.grade==3){
                return false
            }else{
                return true
            }
        },
        correspond4:function(){
            if(this.grade==2){
                return false
            }else{
                return true
            }
        },
        selectAll: {
            get: function () {
                //チェックボックスのすべてにチェックが入ったかを判定
                if(this.grade==1&&this.sections.length == this.sectionlist.length){
                    return true //authorsにすべてチェックを入れるとselectAllはtrueとなりチェックが入る
                }else if(this.grade==2&&this.sections.length==this.sectionlist.length+this.sectionadd1.length+this.sectioncom.length){
                    return true
                }else if(this.grade==3&&this.sections.length==this.sectionlist.length+this.sectionadd1.length+this.sectionadd2.length){
                    return true
                }else{
                    return false
                }
            },
            set: function (value) { //全選択のチェックボックスを操作した場合
                //
                var array=[]
                //チェックが入った場合（trueでの判定）
                if (value&&this.grade==1) {
                    this.sectionlist.forEach(function (item) {
                        array.push(item);
                    });
                        
                }
                else if (value&&this.grade==2) {
                    this.sectionlist.forEach(function (item) {
                        array.push(item);
                    });
                    this.sectionadd1.forEach(function (item) {
                        array.push(item);
                    });
                    this.sectioncom.forEach(function (item) {
                        array.push(item);
                    });
                        
                }
                else if (value&&this.grade==3) {
                    this.sectionlist.forEach(function (item) {
                        array.push(item);
                    });
                    this.sectionadd1.forEach(function (item) {
                        array.push(item);
                    });
                    this.sectionadd2.forEach(function (item) {
                        array.push(item);
                    });
                        
                }

                this.sections=array;

            }
        }
        
    }
});