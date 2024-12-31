import{r as N,a as m,w as A,o as r,c as l,b,u as f,d as U,F as p,Z as L,e as t,f as O,g,h as x,i as R,v as V,t as a,j as E,n as w}from"./app-83d76f56.js";import{_ as z}from"./AuthenticatedLayout-bf38b0e9.js";import{_ as H}from"./RequestShiftModal-55cc9a7c.js";import{F as P}from"./FlashMessage-0505cd64.js";import"./index-beae5e93.js";import"./ApplicationLogo-a9234fe9.js";const Z={class:"py-4 bg-gray-300"},q={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},G={class:"bg-white overflow-hidden sm:rounded-lg"},J={id:"printB",class:"p-6 text-gray-900"},K={class:"flex flex-col text-center w-full mb-8"},Q=t("h1",{class:"sm:text-4xl text-3xl font-mono title-font mb-4 text-gray-900"},"シフト表",-1),X=t("p",{id:"explanation",class:"lg:w-2/3 mx-auto text-center leading-relaxed text-sm text-gray-600"},"変更したい日付を選択して入力してください。",-1),ee={id:"change_shift",class:"mb-4"},te={key:0,class:"mb-4"},oe=t("h3",{class:"mt-8 mb-4 text-xl"},"変更内容",-1),se={class:"table-auto w-full text-left whitespace-no-wrap"},ne=t("thead",null,[t("tr",null,[t("th",{class:"px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500 rounded-l"},"名前"),t("th",{class:"px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500"},"日付"),t("th",{class:"px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500"},"出勤時間"),t("th",{class:"px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500"},"退勤時間"),t("th",{class:"px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500 text-nowrap rounded-r"}," 削除 ")])],-1),re={class:"px-4 py-3 border-b-2 border-gray-200"},le={class:"px-4 py-3 border-b-2 border-gray-200"},ae={class:"px-4 py-3 border-b-2 border-gray-200"},de={class:"px-4 py-3 border-b-2 border-gray-200"},ie={class:"border-b-2 border-gray-200 w-10 text-center"},ce=["onClick"],_e=t("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"size-6"},[t("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"})],-1),ue=[_e],he=t("button",{class:"bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded"}," 変更を確定 ",-1),me={key:1,class:"flex justify-end mt-4"},pe={class:"print_shift"},fe={class:"overflow-x-auto"},ge={key:0,class:"absolute bg-black text-white text-xs rounded py-1 px-2"},xe={key:1,class:"mb-8"},be=t("h1",null,"勤務時間登録を行うとシフト表が表示されます。",-1),ye=[be],ve={key:2,id:"table1",class:"min-w-full bg-white border border-gray-300"},ke={id:"th1",class:"border border-gray-300 bg-gray-100"},we=["value"],Me=["value"],Se=["value"],Te={id:"date",class:"text-center"},je=t("th",{id:"th3",class:"border text-xs border-gray-300 bg-gray-500 text-white"},[t("div",{class:"flex justify-around items-center"},"時間"),t("div",{class:"border-t border-gray-300 my-1"}),t("div",{class:"vertical-text flex justify-around"},"日数")],-1),De={id:"td1",class:"border border-gray-300 text-sm px-2 py-2 bg-gray-500 text-white"},We={key:0,class:"text-center text-xs"},Ce={key:0},Ye={id:"clockOut",class:"border-t"},Ie={id:"td3",class:"border border-gray-300 text-center px-2 py-2"},Be={class:"text-xs"},$e={id:"employee_name",class:"border-t text-xs"},Re={__name:"index",props:{shifts:Array,month:Array,firstMonth:Array,names:Array,totalWorkingTimes:Object,userId:Number,userName:String,userRole:Object,errors:Object},setup(M){const o=M,S=(e,n,s,i)=>{if(n===e&&s===i)return!0},d=N({selectedMonth:o.month[0][1].firstMonth});let u=m([]),_=m([]);const T=()=>{u.value=[],_.value=[],o.month.forEach(e=>{d.selectedMonth===o.month[0][1].firstMonth?e[1]&&u.value.push({Ymd_date:e[1].Ymd_date,full_date:e[1].full_date,date:e[1].date,day_of_week:e[1].day_of_week,year:e[1].year,firstMonth:e[1].firstMonth,secondMonth:e[1].secondMonth,thirdMonth:e[1].thirdMonth}):d.selectedMonth===o.month[0][1].secondMonth?e[2]&&u.value.push({Ymd_date:e[2].Ymd_date,full_date:e[2].full_date,date:e[2].date,day_of_week:e[2].day_of_week,year:e[2].year,firstMonth:e[2].firstMonth,secondMonth:e[2].secondMonth,thirdMonth:e[2].thirdMonth}):e[3]&&u.value.push({Ymd_date:e[3].Ymd_date,full_date:e[3].full_date,date:e[3].date,day_of_week:e[3].day_of_week,year:e[3].year,firstMonth:e[3].firstMonth,secondMonth:e[3].secondMonth,thirdMonth:e[3].thirdMonth})})},j=()=>{_.value=[],o.shifts.forEach((e,n)=>{d.selectedMonth===o.month[0][1].firstMonth?e[0]&&_.value.push({employee_id:e[0].employee_id,clock_in:e[0].clock_in,clock_out:e[0].clock_out,day_of_week:e[0].day_of_week,employee_name:e[0].employee_name,full_date:e[0].full_date,date:e[0].date}):d.selectedMonth===o.month[0][1].secondMonth?e[1]&&_.value.push({employee_id:e[1].employee_id,clock_in:e[1].clock_in,clock_out:e[1].clock_out,day_of_week:e[1].day_of_week,employee_name:e[1].employee_name,full_date:e[1].full_date,date:e[1].date}):e[2]&&_.value.push({employee_id:e[2].employee_id,clock_in:e[2].clock_in,clock_out:e[2].clock_out,day_of_week:e[2].day_of_week,employee_name:e[2].employee_name,full_date:e[2].full_date,date:e[2].date})})};A(()=>d.selectedMonth,()=>{T(),j()},{immediate:!0}),console.log(o.totalWorkingTimes[2][1].total_working_hours),m([""]);const D=e=>{let n=[];return d.selectedMonth===o.month[0][1].firstMonth?n=o.totalWorkingTimes[1][e].total_working_hours:d.selectedMonth===o.month[0][1].secondMonth?n=o.totalWorkingTimes[2][e].total_working_hours:n=o.totalWorkingTimes[3][e].total_working_hours,n};m([""]);const W=e=>{let n=[];return d.selectedMonth===o.month[0][1].firstMonth?n=o.totalWorkingTimes[1][e].attendance_count:d.selectedMonth===o.month[0][1].secondMonth?n=o.totalWorkingTimes[2][e].attendance_count:n=o.totalWorkingTimes[3][e].attendance_count,n},C=()=>{window.print()},c=m([]),Y=e=>{c.value.push(...e)},I=e=>{E.put(`/actualShifts/${e}`,{shiftUpdates:c.value},{onBefore:()=>{confirm("変更しますか？")},onFinish:()=>{v()}}),location.reload()},v=()=>{c.value="",location.reload()};function B(e){c.value.splice(e,1)}const y=m(!1);return(e,n)=>(r(),l(p,null,[b(f(L),{title:"シフト表"}),b(z,{userRole:o.userRole.authority},{default:U(()=>[t("div",Z,[t("div",q,[t("div",G,[t("div",J,[t("div",K,[b(P),t("form",{onSubmit:n[3]||(n[3]=O(s=>I(o.userId),["prevent"]))},[Q,X,t("div",ee,[c.value&&c.value.length>0?(r(),l("div",te,[oe,t("table",se,[ne,t("tbody",null,[(r(!0),l(p,null,g(c.value,(s,i)=>(r(),l("tr",{key:i,class:w({"bg-gray-100":i%2==1})},[t("td",re,a(o.userName),1),t("td",le,a(s.date),1),t("td",ae,a(s.clock_in?s.clock_in:"休日"),1),t("td",de,a(s.clock_out?s.clock_out:"休日"),1),t("td",ie,[t("button",{type:"button",onClick:k=>B(i),class:"bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded text-nowrap"},ue,8,ce)])],2))),128))])]),t("div",{class:"flex justify-around mt-2"},[he,t("button",{type:"button",onClick:v,class:"bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded"}," 変更をクリア ")])])):x("",!0),c.value.length==0||o.shifts.length>0?(r(),l("div",me,[t("button",{type:"button",id:"printBtn",onClick:C,class:"bg-indigo-600 hover:bg-indigo-700 flex text-white py-2 px-4 rounded"}," 印刷 ")])):x("",!0)]),t("div",pe,[t("div",fe,[y.value?(r(),l("div",ge," クリックして月を変更できます ")):x("",!0),o.shifts.length==0?(r(),l("div",xe,ye)):(r(),l("table",ve,[t("thead",null,[t("tr",null,[t("th",ke,[t("div",null,[R(t("select",{"onUpdate:modelValue":n[0]||(n[0]=s=>d.selectedMonth=s),onMouseover:n[1]||(n[1]=s=>y.value=!0),onMouseleave:n[2]||(n[2]=s=>y.value=!1),class:"border-none text-center py-1 bg-gray-100"},[t("option",{value:o.month[0][1].firstMonth},a(o.month[0][1].firstMonth),9,we),t("option",{value:o.month[0][1].secondMonth},a(o.month[0][1].secondMonth),9,Me),t("option",{value:o.month[0][1].thirdMonth},a(o.month[0][1].thirdMonth),9,Se)],544),[[V,d.selectedMonth]])])]),(r(!0),l(p,null,g(f(u),(s,i)=>(r(),l("th",{id:"th2",key:i,class:"border border-gray-300 text-center bg-gray-500 text-white"},[t("div",{class:w([{"hover:bg-indigo-500":s.date>new Date().getDate()},"border-b flex justify-center items-center"])},[b(H,{date:s.date,shifts:f(_),day_of_week:s.day_of_week,full_date:s.full_date,Ymd_date:s.Ymd_date,userId:o.userId,onUpdateShiftData:Y},null,8,["date","shifts","day_of_week","full_date","Ymd_date","userId"])],2),t("div",Te,a(s.day_of_week),1)]))),128)),je])]),t("tbody",null,[(r(!0),l(p,null,g(o.names,(s,i)=>(r(),l("tr",{key:i,class:"hover:bg-blue-200"},[t("td",De,a(s.employee_name),1),(r(!0),l(p,null,g(f(u),(k,$)=>(r(),l("td",{id:"td2",key:$,class:"border border-gray-30 py-2 bg-gray-50"},[(r(!0),l(p,null,g(f(_),(h,F)=>(r(),l("div",{key:F},[S(h.employee_name,s.employee_name,k.date,h.date)?(r(),l("div",We,[h.clock_in===900&&h.clock_out===900?x("",!0):(r(),l("div",Ce,[t("div",null,a(h.clock_in.toString().padStart(4,"0")),1),t("div",Ye,a(h.clock_out.toString().padStart(4,"0")),1)]))])):x("",!0)]))),128))]))),128)),t("td",Ie,[t("div",Be,a(D(s.employee_id)),1),t("div",$e,a(W(s.employee_id)),1)])]))),128))])]))])])],32)])])])])])]),_:1},8,["userRole"])],64))}};export{Re as default};