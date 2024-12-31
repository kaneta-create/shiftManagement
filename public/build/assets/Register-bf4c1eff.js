import{T as f,o as c,p as _,d as i,b as o,u as s,Z as g,e as t,q as d,s as w,n as b,f as V}from"./app-6bd2b245.js";import{_ as y}from"./GuestLayout-09d3f2e9.js";import{_ as r,a as n,b as m}from"./TextInput-a052a7ee.js";import{_ as v}from"./PrimaryButton-a0317264.js";import"./ApplicationLogo-047ef43c.js";const x=t("p",{class:"mb-4 mt-2 text-sm text-gray-500"},"以下のフォームを入力してください。",-1),k={class:"mt-4"},z={class:"mt-4"},q={class:"mt-4"},U={class:"mt-4"},$={class:"flex items-center justify-end mt-4"},j={__name:"Register",setup(h){const e=f({organization_name:"",name:"",employee_number:"",password:"",password_confirmation:"",authority:3,role:"社員",terms:!1}),u=()=>{e.post(route("register"),{onFinish:()=>e.reset("password","password_confirmation")})};return(p,a)=>(c(),_(y,null,{default:i(()=>[o(s(g),{title:"Register"}),x,t("form",{onSubmit:V(u,["prevent"])},[t("div",null,[o(r,{for:"organization_name",value:"組織名"}),o(n,{id:"organization_name",type:"text",class:"mt-1 block w-full",modelValue:s(e).organization_name,"onUpdate:modelValue":a[0]||(a[0]=l=>s(e).organization_name=l),required:"",autofocus:""},null,8,["modelValue"]),o(m,{class:"mt-2",message:s(e).errors.organization_name},null,8,["message"])]),t("div",k,[o(r,{for:"name",value:"名前"}),o(n,{id:"name",type:"text",class:"mt-1 block w-full",modelValue:s(e).name,"onUpdate:modelValue":a[1]||(a[1]=l=>s(e).name=l),required:""},null,8,["modelValue"]),o(m,{class:"mt-2",message:s(e).errors.name},null,8,["message"])]),t("div",z,[o(r,{for:"employee_number",value:"社員番号"}),o(n,{id:"employee_number",type:"text",class:"mt-1 block w-full",modelValue:s(e).employee_number,"onUpdate:modelValue":a[2]||(a[2]=l=>s(e).employee_number=l),required:""},null,8,["modelValue"]),o(m,{class:"mt-2",message:s(e).errors.name},null,8,["message"])]),t("div",q,[o(r,{for:"password",value:"パスワード"}),o(n,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:s(e).password,"onUpdate:modelValue":a[3]||(a[3]=l=>s(e).password=l),required:"",autocomplete:"new-password"},null,8,["modelValue"]),o(m,{class:"mt-2",message:s(e).errors.password},null,8,["message"])]),t("div",U,[o(r,{for:"password_confirmation",value:"確認用パスワード"}),o(n,{id:"password_confirmation",type:"password",class:"mt-1 block w-full",modelValue:s(e).password_confirmation,"onUpdate:modelValue":a[4]||(a[4]=l=>s(e).password_confirmation=l),required:"",autocomplete:"new-password"},null,8,["modelValue"]),o(m,{class:"mt-2",message:s(e).errors.password_confirmation},null,8,["message"])]),t("div",$,[o(s(w),{href:p.route("login"),class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:i(()=>[d(" 登録済みの組織の場合 ")]),_:1},8,["href"]),o(v,{class:b(["ml-4 bg-indigo-500",{"opacity-25":s(e).processing}]),disabled:s(e).processing},{default:i(()=>[d(" 登録 ")]),_:1},8,["class","disabled"])])],32)]),_:1}))}};export{j as default};