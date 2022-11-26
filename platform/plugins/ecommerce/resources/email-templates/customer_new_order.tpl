{{ header }}

<h2>Thank you for your request</h2>

<p>Hi {{ customer_name }},</p>
<p>Thank you for your request, you will receive notification once the talent has accepted or rejected your request</p>

<a href="{{ site_url }}/customer/orders/view/{{ order_id }}" class="button button-blue">View request here</a> or <a href="{{ site_url }}">Explore more</a>

<br />

<h3>Request Details</h3>

<p>Request reference: <strong>#{{ order_id }}</strong></p>

{{ product_list }}

<h3>Payment method</h3>
<p>{{ payment_method }}</p>

<br />

<p>If you have any question, please contact us via <a href="mailto:{{ site_admin_email }}">{{ site_admin_email }}</a></p>

{{ footer }}