import{T as w,o as m,p as n,d as i,b as a,u as s,Z as b,c as x,t as y,h as d,e as t,s as h,q as c,n as k,f as V}from"./app-ae569188.js";import{_ as v}from"./Checkbox-51039c4d.js";import{_ as $}from"./GuestLayout-9feccbd6.js";import{_ as u,a as f,b as p}from"./TextInput-dd1b5b84.js";import{_ as B}from"./PrimaryButton-09282528.js";import"./ApplicationLogo-76ede44f.js";const q={key:0,class:"mb-4 font-medium text-sm text-green-600"},N={class:"mt-4"},C={class:"block mt-4"},L={class:"flex items-center"},P=t("span",{class:"ml-2 text-sm text-gray-600"},"Remember me",-1),R={class:"flex items-center justify-end mt-4"},z={__name:"Login copy",props:{canResetPassword:Boolean,status:String},setup(l){const e=w({email:"",password:"",remember:!1}),_=()=>{e.post(route("login"),{onFinish:()=>e.reset("password")})};return(g,o)=>(m(),n($,null,{default:i(()=>[a(s(b),{title:"Log in"}),l.status?(m(),x("div",q,y(l.status),1)):d("",!0),t("form",{onSubmit:V(_,["prevent"])},[t("div",null,[a(u,{for:"email",value:"Email"}),a(f,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:s(e).email,"onUpdate:modelValue":o[0]||(o[0]=r=>s(e).email=r),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),a(p,{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),t("div",N,[a(u,{for:"password",value:"Password"}),a(f,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:s(e).password,"onUpdate:modelValue":o[1]||(o[1]=r=>s(e).password=r),required:"",autocomplete:"current-password"},null,8,["modelValue"]),a(p,{class:"mt-2",message:s(e).errors.password},null,8,["message"])]),t("div",C,[t("label",L,[a(v,{name:"remember",checked:s(e).remember,"onUpdate:checked":o[2]||(o[2]=r=>s(e).remember=r)},null,8,["checked"]),P])]),t("div",R,[l.canResetPassword?(m(),n(s(h),{key:0,href:g.route("password.request"),class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:i(()=>[c(" Forgot your password? ")]),_:1},8,["href"])):d("",!0),a(B,{class:k(["ml-4",{"opacity-25":s(e).processing}]),disabled:s(e).processing},{default:i(()=>[c(" Log in ")]),_:1},8,["class","disabled"])])],32)]),_:1}))}};export{z as default};