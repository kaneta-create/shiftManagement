import{T as m,x as f,o as a,p as g,d as s,b as i,u as e,Z as p,c as _,h,e as o,q as n,n as y,s as v,f as x}from"./app-6bd2b245.js";import{_ as b}from"./GuestLayout-09d3f2e9.js";import{_ as k}from"./PrimaryButton-a0317264.js";import"./ApplicationLogo-047ef43c.js";const w=o("div",{class:"mb-4 text-sm text-gray-600"}," Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another. ",-1),V={key:0,class:"mb-4 font-medium text-sm text-green-600"},B={class:"mt-4 flex items-center justify-between"},j={__name:"VerifyEmail",props:{status:String},setup(r){const c=r,t=m({}),d=()=>{t.post(route("verification.send"))},l=f(()=>c.status==="verification-link-sent");return(u,E)=>(a(),g(b,null,{default:s(()=>[i(e(p),{title:"Email Verification"}),w,l.value?(a(),_("div",V," A new verification link has been sent to the email address you provided during registration. ")):h("",!0),o("form",{onSubmit:x(d,["prevent"])},[o("div",B,[i(k,{class:y({"opacity-25":e(t).processing}),disabled:e(t).processing},{default:s(()=>[n(" Resend Verification Email ")]),_:1},8,["class","disabled"]),i(e(v),{href:u.route("logout"),method:"post",as:"button",class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:s(()=>[n("Log Out")]),_:1},8,["href"])])],32)]),_:1}))}};export{j as default};