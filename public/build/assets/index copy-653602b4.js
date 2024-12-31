import{r as D,a as k,w as A,o as r,c as d,b as f,u as p,d as v,F as m,Z as F,e as t,i as N,v as S,t as a,g as M,h as y}from"./app-ae569188.js";import{_ as V}from"./AuthenticatedLayout-17c4240c.js";import{_ as B}from"./CreateShiftModal-b636c3d7.js";import{F as E}from"./FlashMessage-d554907c.js";import"./ApplicationLogo-76ede44f.js";import"./index-5d69c520.js";const C=t("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"},"シフト表",-1),$={class:"py-12"},j={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},L={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},O={class:"p-6 text-gray-900"},U={class:"flex flex-col text-centerw-full mb-8"},Z=t("h1",{class:"sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900"},"シフト表",-1),q={class:"overflow-x-auto"},z={class:"min-w-full bg-white border border-gray-300"},G={class:"border border-gray-300"},H=["value"],J=["value"],K=["value"],P=t("div",{class:"border-t border-gray-300"},null,-1),Q=t("div",null,"名前",-1),R={class:"text-center"},X=t("div",{class:"border-t border-gray-300 mt-1"},null,-1),ee=t("th",{class:"border text-xs border-gray-300"},[t("div",{class:""},"時間"),t("div",{class:"border-t border-gray-300 mt-1"}),t("div",{class:"vertical-text"},"日数")],-1),te={class:"border border-gray-300 text-sm px-2 py-2"},oe={key:0,class:"text-center text-xs"},ne={key:0},se={class:"border border-gray-300 text-center px-2 py-2"},le={class:"text-xs"},re={class:"border-t text-xs"},me={__name:"index copy",props:{shifts:Array,month:Array,firstMonth:Array,names:Array,totalWorkingTimes:Object,userId:Number},setup(x){const o=x,g=(e,n,s,h)=>{if(n===e&&s===h)return!0},l=D({selectedMonth:o.month[0][1].firstMonth});let c=k([]),_=k([]);const b=()=>{c.value=[],_.value=[],o.month.forEach(e=>{l.selectedMonth===o.month[0][1].firstMonth?e[1]&&c.value.push({Ymd_date:e[1].Ymd_date,full_date:e[1].full_date,date:e[1].date,day_of_week:e[1].day_of_week,year:e[1].year,firstMonth:e[1].firstMonth,secondMonth:e[1].secondMonth,thirdMonth:e[1].thirdMonth}):l.selectedMonth===o.month[0][1].secondMonth?e[2]&&c.value.push({Ymd_date:e[2].Ymd_date,full_date:e[2].full_date,date:e[2].date,day_of_week:e[2].day_of_week,year:e[2].year,firstMonth:e[2].firstMonth,secondMonth:e[2].secondMonth,thirdMonth:e[2].thirdMonth}):e[3]&&c.value.push({Ymd_date:e[3].Ymd_date,full_date:e[3].full_date,date:e[3].date,day_of_week:e[3].day_of_week,year:e[3].year,firstMonth:e[3].firstMonth,secondMonth:e[3].secondMonth,thirdMonth:e[3].thirdMonth})})},w=()=>{_.value=[],o.shifts.forEach((e,n)=>{l.selectedMonth===o.month[0][1].firstMonth?e[0]&&_.value.push({employee_id:e[0].employee_id,clock_in:e[0].clock_in,clock_out:e[0].clock_out,day_of_week:e[0].day_of_week,employee_name:e[0].employee_name,date:e[0].date}):l.selectedMonth===o.month[0][1].secondMonth?e[1]&&_.value.push({employee_id:e[1].employee_id,clock_in:e[1].clock_in,clock_out:e[1].clock_out,day_of_week:e[1].day_of_week,employee_name:e[1].employee_name,date:e[1].date}):e[2]&&_.value.push({employee_id:e[2].employee_id,clock_in:e[2].clock_in,clock_out:e[2].clock_out,day_of_week:e[2].day_of_week,employee_name:e[2].employee_name,date:e[2].date})})};A(()=>l.selectedMonth,()=>{b(),w()},{immediate:!0}),console.log(o.totalWorkingTimes[2][1].total_working_hours),k([""]);const T=e=>{let n=[];return l.selectedMonth===o.month[0][1].firstMonth?n=o.totalWorkingTimes[1][e].total_working_hours:l.selectedMonth===o.month[0][1].secondMonth?n=o.totalWorkingTimes[2][e].total_working_hours:n=o.totalWorkingTimes[3][e].total_working_hours,Math.floor(n)};k([""]);const W=e=>{let n=[];return l.selectedMonth===o.month[0][1].firstMonth?n=o.totalWorkingTimes[1][e].attendance_count:l.selectedMonth===o.month[0][1].secondMonth?n=o.totalWorkingTimes[2][e].attendance_count:n=o.totalWorkingTimes[3][e].attendance_count,n};return(e,n)=>(r(),d(m,null,[f(p(F),{title:"シフト表"}),f(V,null,{header:v(()=>[C]),default:v(()=>[t("div",$,[t("div",j,[t("div",L,[t("div",O,[t("div",U,[f(E),Z,t("div",q,[t("table",z,[t("thead",null,[t("tr",null,[t("th",G,[t("div",null,[N(t("select",{"onUpdate:modelValue":n[0]||(n[0]=s=>l.selectedMonth=s),class:"border-none text-center py-1"},[t("option",{value:o.month[0][1].firstMonth},a(o.month[0][1].firstMonth),9,H),t("option",{value:o.month[0][1].secondMonth},a(o.month[0][1].secondMonth),9,J),t("option",{value:o.month[0][1].thirdMonth},a(o.month[0][1].thirdMonth),9,K)],512),[[S,l.selectedMonth]])]),P,Q]),(r(!0),d(m,null,M(p(c),(s,h)=>(r(),d("th",{key:h,class:"border border-gray-300 text-center"},[t("div",R,a(s.date),1),X,t("div",null,a(s.day_of_week),1)]))),128)),ee])]),t("tbody",null,[(r(!0),d(m,null,M(o.names,(s,h)=>(r(),d("tr",{key:h},[t("td",te,a(s.employee_name),1),(r(!0),d(m,null,M(p(c),(u,Y)=>(r(),d("td",{key:Y,class:"border border-gray-30 py-2"},[(r(!0),d(m,null,M(p(_),(i,I)=>(r(),d("div",{key:I},[g(i.employee_name,s.employee_name,u.date,i.date)?(r(),d("div",oe,[i.clock_in===900&&i.clock_out===900?y("",!0):(r(),d("div",ne,[f(B,{date:u.date,full_date:u.full_date,Ymd_date:u.Ymd_date,userId:o.userId,clock_in_time:i.clock_in,clock_out_time:i.clock_out,employee_id:s.employee_id},null,8,["date","full_date","Ymd_date","userId","clock_in_time","clock_out_time","employee_id"])]))])):y("",!0)]))),128))]))),128)),t("td",se,[t("div",le,a(T(s.employee_id)),1),t("div",re,a(W(s.employee_id)),1)])]))),128))])])])])])])])])]),_:1})],64))}};export{me as default};