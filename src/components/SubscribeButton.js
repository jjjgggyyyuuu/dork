components_SubscribeButton = () => {
  // Stripe initialization with your publishable key
  let e; 
  let [t, a] = (0, r.useState)(false),
      [i, n] = (0, r.useState)(""),
      l = u.env.NEXT_PUBLIC_STRIPE_PUBLISHABLE_KEY || "";
  
  // Initialize Stripe if key exists
  l && (e = (0, m.J)(l));
  
  // Handle subscription function
  let handleSubscribe = async () => {
    try {
      // Set loading state
      a(true);
      n("");
      
      // Key validation
      if (!l) throw Error("Stripe publishable key is missing");
      if (!e) throw Error("Could not initialize Stripe");
      
      // Call PHP endpoint
      let t = await fetch("/stripe-handler.php?action=create_session", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({})
      });
      
      // Process response
      if (!t.ok) {
        let e = await t.json();
        throw Error(e.error || "Failed to create checkout session")
      }
      
      // Redirect to Stripe checkout
      let {sessionId: s} = await t.json();
      if (!s) throw Error("No session ID returned");
      let r = await e,
          {error: i} = await r.redirectToCheckout({sessionId: s});
      if (i) throw Error(i.message)
    } catch (e) {
      console.error("Subscription error:", e);
      n(e.message || "Failed to initiate subscription. Please try again.")
    } finally {
      a(false);
    }
  };