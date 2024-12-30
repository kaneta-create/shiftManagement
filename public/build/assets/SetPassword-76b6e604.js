import{Q as c,T as _,o as f,p as w,d as n,b as o,u as e,Z as b,e as t,q as y,n as g,f as V}from"./app-8b9ecc7e.js";import{_ as v}from"./GuestLayout-a1ccc825.js";import{_ as l,a as m,b as d}from"./TextInput-e383d031.js";import{_ as x}from"./PrimaryButton-7534f7a1.js";import"./ApplicationLogo-8621cb80.js";const k=t("div",{class:"mb-4 text-sm text-gray-600"}," パスワードを設定してくだい ",-1),q={class:"mt-2"},S={class:"mt-2"},$={class:"mt-2"},U={class:"flex items-center justify-end mt-4"},j={__name:"SetPassword",setup(B){const i=c(),s=_({password:"",confirmation_password:"",temporary_password:"",employee_number:""}),p=()=>{if(s.password!=s.confirmation_password){s.errors.confirmation_password="パスワードと確認パスワードが一致しません";return}else s.put(route("passwordSets.storePassword"),{onSuccess:()=>{confirm("登録が完了しました。ログインしますか？")},onFinish:u=>{if(i.props.flash.message!=null){confirm("パスワードは設定済みです。ログイン画面からログインしてください。");return}s.reset("password","password_confirmation","temporary_password")}})};return(u,a)=>(f(),w(v,null,{default:n(()=>[o(e(b),{title:"Set Password"}),k,t("form",{onSubmit:V(p,["prevent"])},[t("div",null,[o(l,{for:"employee_number",value:"社員番号"}),o(m,{id:"employee_number",name:"employee_number",type:"number",class:"mt-1 block w-full",modelValue:e(s).employee_number,"onUpdate:modelValue":a[0]||(a[0]=r=>e(s).employee_number=r),required:"",autofocus:""},null,8,["modelValue"]),o(d,{class:"mt-2",message:e(s).errors.password},null,8,["message"])]),t("div",q,[o(l,{for:"temporary_password",value:"仮パスワード"}),o(m,{id:"temporary_password",name:"temporary_password",type:"text",class:"mt-1 block w-full",modelValue:e(s).temporary_password,"onUpdate:modelValue":a[1]||(a[1]=r=>e(s).temporary_password=r),required:"",autofocus:""},null,8,["modelValue"]),o(d,{class:"mt-2",message:e(s).errors.password},null,8,["message"])]),t("div",S,[o(l,{for:"password",value:"パスワード"}),o(m,{id:"password",name:"password",type:"password",class:"mt-1 block w-full",modelValue:e(s).password,"onUpdate:modelValue":a[2]||(a[2]=r=>e(s).password=r),required:"",autofocus:""},null,8,["modelValue"]),o(d,{class:"mt-2",message:e(s).errors.password},null,8,["message"])]),t("div",$,[o(l,{for:"confirmation_password",value:"確認用パスワード"}),o(m,{id:"confirmation_password",name:"confirm_password",type:"password",class:"mt-1 block w-full",modelValue:e(s).confirmation_password,"onUpdate:modelValue":a[3]||(a[3]=r=>e(s).confirmation_password=r),required:"",autofocus:""},null,8,["modelValue"]),o(d,{class:"mt-2",message:e(s).errors.confirmation_password},null,8,["message"])]),t("div",U,[o(x,{id:"register-button",class:g(["bg-indigo-500",{"opacity-25":e(s).processing}]),disabled:e(s).processing},{default:n(()=>[y(" 登録 ")]),_:1},8,["class","disabled"])])],32)]),_:1}))}};export{j as default};