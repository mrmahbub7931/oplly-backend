{{ header }}

<h2>Payment for Request #{{ Request_id }} was confirmed!</h2>
<p>Hi {{ customer_name }},</p>
<p>Thank you for purchasing our product.</p>

<a href="{{ site_url }}/orders/tracking?Request_id={{ Request_id }}&email={{ customer_email }}" class="button button-blue">View Request</a> or <a href="{{ site_url }}">Go to our shop</a>

<br />

<h3>Request information: </h3>

<p>Request number: <strong>#{{ Request_id }}</strong></p>

{{ product_list }}

<h3>Customer information</h3>

<p>{{ customer_name }} - {{ customer_phone }}, {{ customer_address }}</p>

<h3>Payment method</h3>
<p>{{ payment_method }}</p>

<br />

<p>If you have any question, please contact us via <a href="mailto:{{ site_admin_email }}">{{ site_admin_email }}</a></p>

{{ footer }}
