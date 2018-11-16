$.sessionTimeout({
    keepAliveInterval: 600000, 
    keepAliveUrl: '/ajax/keepalive',
    logoutUrl: '/users/account/logout',
    redirUrl: '#',
    warnAfter: 1.2e+6,
    redirAfter: 1.8e+6,
    countdownBar: true,
    ignoreUserActivity: false,
    countdownSmart: true,
    message: 'You have been inactive for a while, so you will be logged out soon'
});
