
@if(settings('chat_status', 31) == 1)
    {{--  tak.to chat  --}}
    @if(settings('tawk_to_status', 31))
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = '{{ settings('s1_src_link_tawk_to', 31) }}';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
    @endif



    {{--  crisp chat  --}}
    @if(settings('crisp_chat_status', 31))
    <script type="text/javascript">
        window.$crisp=[];
        window.CRISP_WEBSITE_ID="{{ settings('crisp_chat_id', 31) }}";
        (function(){d=document;s=d.createElement("script");
        s.src="https://client.crisp.chat/l.js";
        s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();
    </script>
    @endif




    {{--  livechatinc chat  --}}
    @if(settings('livechatinc_chat_status', 31))
    <!-- Start of LiveChat (www.livechat.com) code -->
    <script>
        window.__lc = window.__lc || {};
        window.__lc.license = {{ settings('livechatinc_chat_id', 31) }};
        window.__lc.integration_name = "manual_onboarding";
        window.__lc.product_name = "livechat";
        ;(function(n,t,c){function i(n){return e._h?e._h.apply(null,n):e._q.push(n)}var e={_q:[],_h:null,_v:"2.0",on:function(){i(["on",c.call(arguments)])},once:function(){i(["once",c.call(arguments)])},off:function(){i(["off",c.call(arguments)])},get:function(){if(!e._h)throw new Error("[LiveChatWidget] You can't use getters before load.");return i(["get",c.call(arguments)])},call:function(){i(["call",c.call(arguments)])},init:function(){var n=t.createElement("script");n.async=!0,n.type="text/javascript",n.src="https://cdn.livechatinc.com/tracking.js",t.head.appendChild(n)}};!n.__lc.asyncInit&&e.init(),n.LiveChatWidget=n.LiveChatWidget||e}(window,document,[].slice))
    </script>
    <noscript><a href="https://www.livechat.com/chat-with/{{ settings('livechatinc_chat_id', 31) }}/" rel="nofollow">Chat with us</a>, powered by <a href="https://www.livechat.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a></noscript>
    <!-- End of LiveChat code -->
    @endif




    {{--  intercom chat  --}}
    @if(settings('intercom_chat_status', 31))
    <script>
        window.intercomSettings = {
        api_base: "https://api-iam.intercom.io",
        app_id: "{{ settings('intercom_chat_id', 31) }}",
        user_id: {{ auth()->guard('customer')->user()->id ?? 0  }}, // IMPORTANT: Replace "user.id" with the variable you use to capture the user's ID
        name: '{{ auth()->guard('customer')->user()->name ?? 0  }}', // IMPORTANT: Replace "user.name" with the variable you use to capture the user's name
        email: '{{ auth()->guard('customer')->user()->email ?? 0  }}', // IMPORTANT: Replace "user.email" with the variable you use to capture the user's email address
        created_at: {{ auth()->guard('customer')->user()->created_at ?? 0  }}, // IMPORTANT: Replace "user.createdAt" with the variable you use to capture the user's sign-up date
        };
    </script>

    {{--  intercom chat  --}}
    <script>
        // We pre-filled your app ID in the widget URL: 'https://widget.intercom.io/widget/zb20exwq'
        (function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',w.intercomSettings);}else{var d=document;var i=function(){i.c(arguments);};i.q=[];i.c=function(args){i.q.push(args);};w.Intercom=i;var l=function(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/zb20exwq';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);};if(document.readyState==='complete'){l();}else if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
    </script>
    @endif




    {{--  tidio chat  --}}
    @if(settings('tidio_chat_status', 31))
        <script src="//code.tidio.co/{{ settings('tidio_chat_public_key', 31) }}.js" async></script>
    @endif




    {{--  tiledesk chat  --}}
    @if(settings('tiledesk_chat_status', 31))
    <script type="application/javascript">
        window.tiledeskSettings=
        {
            projectid: "{{ settings('tiledesk_chat_id', 31) }}",
        };
        (function(d, s, id) {
            var w=window; var d=document; var i=function(){i.c(arguments);};
            i.q=[]; i.c=function(args){i.q.push(args);}; w.Tiledesk=i;
            var js, fjs=d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js=d.createElement(s);
            js.id=id; js.async=true; js.src="https://widget.tiledesk.com/v6/launch.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document,'script','tiledesk-jssdk'));
    </script>
    @endif


    
@endif
