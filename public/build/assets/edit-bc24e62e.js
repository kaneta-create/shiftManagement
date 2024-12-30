import{r as k,k as j,o as a,c as l,b as f,u as p,d as $,F as c,Z as S,e,f as V,i as b,A as C,g as _,j as O,t as u,h,n as g,v as y}from"./app-8b9ecc7e.js";import{_ as E}from"./AuthenticatedLayout-2d6fda54.js";import"./index-bfccac7f.js";import"./ApplicationLogo-8621cb80.js";const M={class:"py-4 bg-gray-300"},U={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},B={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},L={class:"p-6 text-gray-900"},R=e("div",null,[e("h1",{class:"sm:text-4xl text-center text-3xl font-mono title-font mb-4 text-gray-900"},"シフト登録"),e("p",{class:"lg:w-2/3 mx-auto text-center leading-relaxed text-sm text-gray-600"},"社員番号と時間を入力して勤務時間を変更してください")],-1),D={class:"text-center my-8"},F=e("label",{for:"employee_number",class:"mr-2"},"社員番号:",-1),T={key:0,class:"text-red-600"},H={key:1,class:"text-red-600"},q={class:"lg:w-3/4 w-full mx-auto overflow-auto"},z={class:"table-fixed w-full"},A=e("thead",null,[e("tr",null,[e("th",{class:"px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500 rounded-tl rounded-bl"},"曜日"),e("th",{class:"px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500"},"出勤時間"),e("th",{class:"px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500"},"退勤時間"),e("th",{class:"px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-500"}," 削除 ")])],-1),Z={class:"border-y-2 border-gray-200 px-4 py-3 text-center"},G={class:"border-y-2 border-gray-200 px-4 py-3 relative"},I=["onUpdate:modelValue"],J=e("option",{value:"",disabled:""},"-- 選択 --",-1),K=["value"],P={key:0,class:"absolute mb-5 text-red-600 text-sm -bottom-6 left-0 whitespace-nowrap"},Q={class:"border-y-2 border-gray-200 px-4 py-3"},X=["onUpdate:modelValue"],Y=e("option",{value:"",disabled:""},"-- 選択 --",-1),W=["value"],ee={class:"border-y-2 border-gray-200 px-4 py-3 w-full"},te=["disabled","onClick"],oe=e("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"w-6 h-6"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"})],-1),se=[oe],re=e("div",{class:"mt-6"},[e("button",{dusk:"update-defaultShift-button",class:"flex mx-auto text-white bg-indigo-600 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-700 rounded text-lg"}," 更新 ")],-1),me={__name:"edit",props:{defaultShifts:Object,employeeNumber:Number,employee_id:Number,userRole:Object,errors:Object},setup(w){const n=w;console.log(n.employeeNumber);const d={1:"月曜日",2:"火曜日",3:"水曜日",4:"木曜日",5:"金曜日",6:"土曜日",7:"日曜日"},o=k({employeeNumber:n.employeeNumber,月曜日:{start_time:"",end_time:"",dayOfNameNumber:""},火曜日:{start_time:"",end_time:"",dayOfNameNumber:""},水曜日:{start_time:"",end_time:"",dayOfNameNumber:""},木曜日:{start_time:"",end_time:"",dayOfNameNumber:""},金曜日:{start_time:"",end_time:"",dayOfNameNumber:""},土曜日:{start_time:"",end_time:"",dayOfNameNumber:""},日曜日:{start_time:"",end_time:"",dayOfNameNumber:""}});j(()=>{Object.entries(d).forEach(([r,s])=>{o[s].dayOfNameNumber=r,o[s].start_time=="00:00"&&o[s].end_time=="00:00"&&(o[s].start_time="",o[s].end_time="")})}),n.defaultShifts.forEach(r=>{console.log(r),Object.entries(d).forEach(([s,t])=>{r.day_of_week==s&&(o[t].start_time=r.clock_in.slice(0,-3),o[t].end_time=r.clock_out.slice(0,-3))})});const v=r=>{Object.entries(d).forEach(([s,t])=>{r==s&&(o[t].start_time="",o[t].end_time="")})},N=r=>{O.put(`/defaultShifts/${r}`,o)},x=(()=>{const r=[];for(let s=0;s<24;s++)for(let t=0;t<60;t+=15){const m=String(s).padStart(2,"0"),i=String(t).padStart(2,"0");r.push(`${m}:${i}`)}return r})();return(r,s)=>(a(),l(c,null,[f(p(S),{title:"出勤時間変更"}),f(E,{userRole:n.userRole.authority},{default:$(()=>[e("div",M,[e("div",U,[e("div",B,[e("div",L,[R,e("form",{onSubmit:s[1]||(s[1]=V(t=>N(n.employee_id),["prevent"]))},[e("div",D,[F,b(e("input",{type:"text",id:"employee_number",required:"",name:"employee_number","onUpdate:modelValue":s[0]||(s[0]=t=>o.employeeNumber=t),class:"rounded"},null,512),[[C,o.employeeNumber]])]),(a(!0),l(c,null,_(Object.keys(d),(t,m)=>(a(),l("div",{key:m},[n.errors&&n.errors[`${d[t]}.start_time`]?(a(),l("div",T," *"+u(n.errors[`${d[t]}.start_time`]),1)):h("",!0),n.errors&&n.errors[`${d[t]}.end_time`]?(a(),l("div",H," *"+u(n.errors[`${d[t]}.end_time`]),1)):h("",!0)]))),128)),e("div",q,[e("table",z,[A,e("tbody",null,[(a(),l(c,null,_(d,(t,m)=>e("tr",{key:m,class:g({"bg-gray-50":m%2!==1})},[e("td",Z,u(t),1),e("td",G,[b(e("select",{"onUpdate:modelValue":i=>o[t].start_time=i,class:"mb-2 rounded w-full"},[J,(a(!0),l(c,null,_(p(x),i=>(a(),l("option",{key:i,value:i},u(i),9,K))),128))],8,I),[[y,o[t].start_time]]),o[t].start_time&&o[t].end_time&&o[t].start_time>=o[t].end_time?(a(),l("div",P," *出勤時間は退勤時間より前に設定してください。 ")):h("",!0)]),e("td",Q,[b(e("select",{"onUpdate:modelValue":i=>o[t].end_time=i,class:"mb-2 rounded w-full"},[Y,(a(!0),l(c,null,_(p(x),i=>(a(),l("option",{key:i,value:i},u(i),9,W))),128))],8,X),[[y,o[t].end_time]])]),e("td",ee,[e("button",{disabled:!o[t].start_time,class:g([{"text-white bg-red-600 hover:bg-red-700":o[t].start_time,"text-gray-500 bg-gray-300 cursor-not-allowed":!o[t].start_time},"border-0 py-2 px-8 focus:outline-none rounded flex justify-center ml-8"]),type:"button",onClick:i=>v(o[t].dayOfNameNumber)},se,10,te)])],2)),64))])])]),re],32)])])])])]),_:1},8,["userRole"])],64))}};export{me as default};