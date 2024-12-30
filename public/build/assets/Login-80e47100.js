import{T as _,o as n,p as b,d as l,b as o,u as e,Z as g,c as w,t as y,h as x,e as t,q as i,s as k,n as h,f as V}from"./app-8b9ecc7e.js";import{_ as v}from"./Checkbox-d835f949.js";import{_ as $}from"./GuestLayout-a1ccc825.js";import{_ as d,a as u,b as c}from"./TextInput-e383d031.js";import{_ as B}from"./PrimaryButton-7534f7a1.js";import"./ApplicationLogo-8621cb80.js";const N={key:0,class:"mb-4 font-medium text-sm text-green-600"},S={class:"mt-4"},q={class:"block mt-4"},C={class:"flex items-center"},U=t("span",{class:"ml-2 text-sm text-gray-600"},"ログインを保持する",-1),L={class:"flex items-center justify-between mt-4"},P={__name:"Login",props:{canResetPassword:Boolean,status:String},setup(m){const s=_({employee_number:"",password:"",remember:!1}),p=()=>{s.post(route("login"))};return(f,a)=>(n(),b($,null,{default:l(()=>[o(e(g),{title:"Log in"}),m.status?(n(),w("div",N,y(m.status),1)):x("",!0),t("form",{onSubmit:V(p,["prevent"])},[t("div",null,[o(d,{for:"employee_number",value:"社員番号"}),o(u,{id:"employee_number",name:"employee_number",type:"number",class:"mt-1 block w-full",modelValue:e(s).employee_number,"onUpdate:modelValue":a[0]||(a[0]=r=>e(s).employee_number=r),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),o(c,{class:"mt-2",message:e(s).errors.employee_number},null,8,["message"])]),t("div",S,[o(d,{for:"password",value:"パスワード"}),o(u,{id:"password",name:"password",type:"password",class:"mt-1 block w-full",modelValue:e(s).password,"onUpdate:modelValue":a[1]||(a[1]=r=>e(s).password=r),required:"",autocomplete:"current-password"},null,8,["modelValue"]),o(c,{class:"mt-2",message:e(s).errors.password},null,8,["message"])]),t("div",q,[t("label",C,[o(v,{name:"remember",checked:e(s).remember,"onUpdate:checked":a[2]||(a[2]=r=>e(s).remember=r)},null,8,["checked"]),U])]),t("div",L,[o(e(k),{href:f.route("passwordSets.index"),dusk:"initial-login-link",class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:l(()=>[i(" 初回ログインはこちら ")]),_:1},8,["href"]),o(B,{id:"register-button",class:h(["ml-4 bg-indigo-500",{"opacity-25":e(s).processing}]),disabled:e(s).processing},{default:l(()=>[i(" ログイン ")]),_:1},8,["class","disabled"])])],32)]),_:1}))}};export{P as default};
