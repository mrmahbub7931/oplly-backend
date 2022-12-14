{{ header }}

<h2>Your order has been cancelled</h2>

<p>Hi {{ customer_name }},</p>
<p>Your order <strong>{{ order_id }}</strong> has been cancelled as you requested and your payment is in the process of being refunded</p>

<br />

<p>If you have any question, please contact us via <a href="mailto:{{ site_admin_email }}">{{ site_admin_email }}</a></p>

{{ footer }}
