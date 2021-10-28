<?php
	include("extensions/functions.php");
	require_once("extensions/db.php");

	if(empty($_SESSION['joiner']) && empty($_SESSION['organizer'])) header("Location: login.php");
?>

<!-- Head -->
<?php include("includes/head.php"); ?>
<!-- End of Head -->

	<style>
		/* Header Area */
		header{background:url(images/header-bg.png) no-repeat center top/cover, #fff;}
		.main_logo{position:static;margin-left:10px;}

		/* Main Area */
		main{width:100%;flex:4;float:none;height:auto;background:none;margin:0;padding:50px 0;border-radius:0;text-align:center;}
		main h2{}

		.place_info{margin:0;}
		.main_info{width:100%;padding:0;}
		.main_info h1{font:600 50px/100% Montserrat,sans-serif;margin:0 0 25px;}
		.main_info h2{font:500 40px/100% Montserrat,sans-serif;color:#313131;margin-bottom:10px;text-align:left;}
		.main_info p{font:400 18px/30px Montserrat,sans-serif;}
		.main_info ul{font:400 18px/30px Montserrat,sans-serif;text-align:left;list-style-type:square;padding-left:50px;}
		.another_list ul{font:400 18px/30px Montserrat,sans-serif;text-align:left;list-style-type:circle;padding-left:50px;}
		.another_list2 ol{font:400 18px/30px Montserrat,sans-serif;text-align:left;padding-left:50px;}
	</style>

	<!--?php wp_head(); ?-->
</head>
	<body>
		<div class="protect-me">
		<div class="clearfix">

<!-- Header -->
<?php include("includes/header.php"); ?>
<!-- End Header -->

<!-- Navigation -->
<?php
	$currentPage = 'adventures';
	include("includes/nav.php");
?>
<!-- End Navigation -->

<!-- Main -->
<div id="main_area">
	<div class="wrapper">
		<div class="breadcrumbs">
			<a href="index.php">Home</a> &#187; <a href="adventures.php">Adventures</a> &#187; Terms and Conditions
		</div>
		<div class="main_con">

			<main>
				<div class="place_info">
					<div class="main_info">
						<h1>Terms and Conditions</h1>
						<section>
							<h2>TERMS OF USE</h2><br>
							<p>THESE TERMS OF USE MUST BE READ BEFORE USING THE SITE. THE USAGE OF ANY PART OF THE SITE INDICATES ACCEPTANCE OF THESE TERMS OF USE. 
								The www.baipajoin.com site and the app ("Site") is managed by Team BaiPaJoin under the supervision of the College of Computer Studies in University of Cebu – Main Campus referred as ("we", "us", "our" or “BaiPaJoin”). By accessing and/or using any part of the Site, you acknowledge that you have read and understood, and agree to the Terms of Use (“Terms”) and other terms and conditions in relation to the Site as referred to in these Terms. If you do not agree to be bound by these Terms, you may not access or use any part of the Site. These Terms constitute a binding legal agreement between you as an individual user (“you” or “your”) and BaiPaJoin. You must be at least eighteen (18) years old to use the Site. Please note that we may change, modify, add and delete these Terms at any time where this is necessary to comply with any law or regulation binding on us or to reflect a change to our operational practices, provided that we will use reasonable endeavors to provide notice of material changes on the Site. Every time you use the Site, please check these Terms to ensure that you have reviewed the current version. By continuing to use any part of the Site after such changes to these Terms, you agree and consent to the changes.
							</p>
						</section>
						<section>
							<h2>SCOPE OF OUR SERVICES</h2>
							<p>
								<ul>
									<li>Through the Site, BaiPaJoin provides an online platform of Cebu’s best tourist attraction where you can browse different types of experiences and tours. Users can make bookings of services provided by tourist attraction operators, travel agencies, and/or any other service providers (“Organizers”) on the Site. By placing an order through the Site, you will be able to book and/or purchase experiences and tours. We will provide a booking confirmation via email. We reserve the right to refuse the booking in accordance with these Terms.
									</li><br>
									<li>Although we will use our expertise with caution in performing the Services, we do not verify, and do not guarantee, that all information provided by Vendors that is available on the Site is accurate, complete, correct or the latest available, and we are not responsible for any errors (including placement and typing errors), obstruction (either because of temporary and/or partial, damage, repair or improvement to the Site or otherwise), inaccurate, misleading or false information of Vendors or non-delivery of information by Organizers.
									</li><br>
									<li>Changes in market conditions or circumstances that occur can lead to changes in a short time causing the information provided by Organizer that is available on the Site to be inaccurate or not applicable. In case of any problems, please contact BaiPaJoin Customer Service at teambaipajoincebu@gmail.com
									</li><br>
									<li>In providing the Services we provide an online platform to connect you with Organizers. To the extent permitted by law, we are not responsible or liable for the acts or omissions of an Organizer. We do not make any representations and should not be construed as making any recommendations or suggestions of the level of service quality or rating of the Organizers listed on the Site, and in no event shall we be responsible or liable for any information, content, products, services or other materials provided or made available by Organizers. The given rating is calculated based on automated algorithms that can be updated and changed as per our discretion.
									</li><br>
									<li>To the extent permitted by law, we have the right to not accept any user (Joiner) or booking (or in certain cases cancel the booking confirmation) at our sole discretion and for any cause without giving reasons for the rejection/refusal/cancellation. The reasons for rejecting a user or booking or cancelling a booking confirmation may include but are not limited to: breach of these Terms, Community quarantine or lockdown imposed by the national authorities or LGU’s, fraud or theft (or indication or suspicion of fraud or theft), suspicion of criminal activity, suspicious ordering, services not being available or no longer being made available by the Vendor, user providing inaccurate, erroneous or misleading information, problems with credit card electronic communications, information or transactions, inappropriate behavior, threats, insults, refusal to provide information, practical impediments, communication difficulties or breakdowns, a Real Mistake (hereinafter described below), history of breaches of these Terms. We can at any time delete or remove the account of any user of the Site, either temporarily or permanently. Removed Users must not attempt to use the Site in any other name or through other users.
									</li><br>
									<li>In a particular case, we may cancel or reject reservations with respect to a "Real Mistake", which does not depend on where the error originated. A Real Mistake is a fault on the Site (for example, in terms of price) which no reasonable person would consider appropriate or to make business sense. The amount charged shall be reimbursed without further charges in such a case.
									</li><br>
									<li>Every payment related to the booking or reservation of the Services shall be made directly to BaiPaJoin payment channels. All payments will be the property of BaiPaJoin and amounts will be due and payable to the relevant Organizer when the services to which a payment relates are provided or at any other applicable time required by the relevant Organizer. BaiPaJoin shall not be responsible for the validity of the reservation, if the payment is not being made directly to BaiPaJoin payment channels.
									</li><br>
								</ul>
							</p>
						</section>
						<section>
							<h2>CANCELLATIONS</h2>
							<p>
								<ul>
									<li>
									By making a booking, order or reservation through the Site, you accept and agree to the terms and conditions of applicable Organizer, including policies regarding cancellation and/or absence, or your specific requests which may be given to the Organizer. BaiPaJoin is not responsible for any violation of these terms and conditions, or which are based on the Joiner’s specific requests, so please read the Organizers’ terms and conditions carefully before you make a booking, order or reservation through the Site.
									</li><br>
									<li>
									Regarding refunds, including by means of credit card chargeback, and subject to any applicable rights you may have at law, BaiPaJoin may have the right to withhold or take part of the amount paid to reimburse the reasonable costs that have been incurred in connection with the cancellation.
									</li><br>
								</ul>
							</p>
						</section>
						<section>
							<h2>SPECIAL REQUESTS</h2>
							<p>
								<ul>
									<li>
									In the event of any special requests (special equipment/tools needed for the tour packages or equivalent, add-ons), the user can create the request once a booking is made on the Site. The request will be addressed at the Organizer’s and BaiPaJoin’s discretion, based on availability and other factors. Your special requests may be subject to additional charges and/or fees by the relevant Organizer based on the discretion and/or policy of the relevant Organizer. BaiPaJoin is not responsible for the availability and/or fulfillment of your special requests by the Organizer.
									</li><br>
									<li>
									In the event of rescheduling or modification of the reservation by the user (Joiner), BaiPaJoin reserves its rights to cancel or reject any rescheduling request if the initial booking is no longer valid (including but not limited to instances where booking has actually been used or refunded) based on the information received by BaiPaJoin from its Organizer.
									</li><br>
									<li>
									For any rescheduling and/or modifications and/or changes of the reservation by the user (including but not limited to changes of date and/or add-ons) made directly through its Vendors, BaiPaJoin is not responsible directly or indirectly for matters relating to rescheduling and/or modifications and/or changes to your reservation, including but not limited to:
										<div class = "another_list">
										<ul><br>
											<li>
												updating the booking reservation on the Site and/or informing to you regarding your reservation that has been modified or rescheduled or changed directly through the Vendors;
											</li><br>
											<li>
												any losses, claims or legal consequences arising from the rescheduling and/or modifications and/or changes to your reservation made directly through the Vendors.
											</li><br>
										</ul>
										</div>
								</ul>
							</p>
						</section>
						<section>
							<h2>TRAVEL ADVICE</h2>
							<p>
								<ul>
									<li>
									By displaying particular destinations, BaiPaJoin does not represent or warrant that travel to such destinations is advisable or risk-free and BaiPaJoin is not liable for damages or losses that may result from travel to such destinations. Under no circumstances shall BaiPaJoin be liable for any incidents occurring during your adventure. You are personally responsible for the selection of travel, travel route and destination, for the entire duration of your adventure. To the extent permitted by law, BaiPaJoin is not responsible for any loss that occurs if you fail to bring the required travel documents, such as your passport, IDs’, Booking Itinerary (Printed and/or Electronic Copy) and any other reasons caused by you.
									</li><br>
									<li>
									You shall be solely responsible for obtaining, maintaining and having available for presentation, the proper and valid travel permits or foreign entry requirements (including, but not limited to, visas or other travel permits and documents, whether for transit or otherwise) applicable to you prior to finalizing your travel arrangements in accordance with the prevailing laws of the country you are traveling from, into, over or transiting in. BaiPaJoin has no obligation and is not responsible for notifying you of the travel arrangements and permits necessary for you to be able to carry out your travel plans. In no event shall BaiPaJoin be responsible or liable for any losses or damages arising out of or in relation to your travel permits.
									</li><br>
								</ul>
							</p>
						</section>
						<section>
							<h2>RATING</h2>
							<p>
								<ul>
									<li>
									Ratings shown on the Site are only provided for the information of users (Joiners) only, and existing ratings are based on information given by BaiPaJoin users (Joiners) itself. We do not verify the rating given and are therefore not responsible for the accuracy of the existing rating, nor do the ratings constitute any endorsement (or otherwise) by us. In no event shall BaiPaJoin be responsible or liable for any claims, losses or liability with respect to the ratings shown on the Site.
									</li><br>
								</ul>
							</p>
						</section>
						<section>
							<h2>PRICE AND PROMOTION</h2>
							<p>
								<ul>
									<li>
									If there is any promotion provided directly by an Organizer, then the rights and authority over the promotion will be fully under that Vendor’s separate terms and conditions and will not apply to the reservation conducted through BaiPaJoin.
									</li><br>
									<li>
									We may offer lower prices and/ or promotions from time to time. Please note that these may involve different conditions and requirements as it relates to booking and refund policies.
									</li><br>
								</ul>
							</p>
						</section>
						<section>
							<h2>ADDITIONAL CHARGES AND REFUNDS</h2>
							<p>
								<ul>
									<li>
									Each price listed on the Site is only available with certain conditions and these prices may change depending on the availability of booking, length of booking and/or other factors. Available prices can include additional taxes and other charges, but in certain circumstances it may not include taxes and other service charges (e.g., tips for tour guides, other facilities charges (if any), and other charges/fees which may arise from the use of services other than those provided by BaiPaJoin (if any)). You agree that you are responsible for verifying the total cost to be paid and other terms and details when the confirmation email is sent. You must verify the booking on the booking summary page, and you may cancel the booking 10 days before the adventure date. Prices shown are detailed so that you can see the amount to be paid, any additional costs due to the use of credit cards and/or inter-bank fees and/or any transaction fee imposed by the payment gateway will be charged to you. If there is a discrepancy in the amount paid, BaiPaJoin will provide an email notification of the amount to be paid by you. In the event that you cancel the booking, BaiPaJoin will refund the amount paid or, subject to any applicable rights you may have at law, refund an amount reduced by the reasonable costs incurred by BaiPaJoin as a result of the cancelation. If you have any questions about the Services, you can contact BaiPaJoin Customer Service at teambaipajoincebu@gmail.com. 
									</li><br>
									<li>
									For any other cancellations, subject to any applicable rights you may have at law, as well as BaiPaJoin’s and the Organizer’s policy, BaiPaJoin will first investigate and verify applicable booking prior to providing refunds of the same amount paid by you, less any applicable costs incurred by BaiPaJoin, including however not limited to processing and administrative surcharges, inter-bank transfer fees, credit card fees etc. (“Refund Amount”). 
									</li><br>
									<li>
									BaiPaJoin is not responsible or liable for any cancelled or expired booking reservation caused by inaccurate transfer amounts, or exceeding the time limit for transfers or any payment that is not being made directly to BaiPaJoin payment channels.	
									</li><br>
									<li>
									You can contact Traveloka Customer Service for further details on the estimated duration for receiving your refund and we will assist you as best as we can.	
									</li><br>
								</ul>
							</p>
						</section>
						<section>
							<h2>ADDITIONAL CHARGES FROM LGUs AND TOURISM OFFICE</h2>
							<p>
								<ul>
									<li>
									In some jurisdictions, LGU’s and Tourism Office may be required by law to directly collect occupancy tax or local city tax from guests. Government authorities may also declare additional taxes and may require the Organizer to collect such taxes directly. You agree to pay any and all of such taxes/costs directly during or before the adventure starts, unless otherwise specified. If you have any questions, please contact Traveloka Customer Service regarding any additional costs that may incur along with the adventure. 
									</li><br>
									<li>
									Certain Organizers may add fees for transport or transfer to and from the destination. This is a common practice for travelling between islands and/or mountainous (such as the Camotes, Bantayan, Dalaguete, Badian), in order to reach the spot or attraction. Such transportation is always governed by the LGU and/or private entity and is offered by or on behalf of the organizer, which is responsible for the transportation service. BaiPaJoin does not arrange any transport and is not responsible for such transport service. You agree that BaiPaJoin is not responsible for the quality, safety, frequency or service level of the transportation services, and for any loss or damage that may result from the use of such transportation services.
									</li><br>
								</ul>
							</p>
						</section>
						<section>
							<h2>JOINER AND ORGANIZATION ACCOUNT</h2>
							<p>
								<ul>
									<li>
									You must create an account to use the Services. We will collect and process your personal information, such as your name, electronic mail (e-mail) address, and your mobile phone number when you register to set up an account. You must provide us with an accurate, complete, and latest information and agree to provide us with any proof of identity that we may reasonably request. We will collect, use, disclose and process your personal information in accordance with our Privacy Policy. 
									</li><br>
									<li>
									Only you can use your own account and you represent and warrant that you will not authorize any other party to use your identity or your account for any reason, unless permitted by BaiPaJoin.
									</li><br>
									<li>
									You cannot assign or transfer your account to any other party.
									</li><br>
									<li>
									You must maintain the security and confidentiality of your account password and any identification we provide to you. In the event of any disclosure of your password, in any way whatsoever resulting in any unauthorized use of your account or identity, order(s) received from such unauthorized use shall still be considered as valid orders and we will process such order(s). Subject to any rights you may have at law, you hereby declare that BaiPaJoin is not responsible for any loss or damage arising from the misuse of your account in accordance with this clause.
									</li><br>
									<li>
									If you no longer have control over your account, you are required to immediately notify us (e.g., your account is hacked in any way or your phone is stolen) so we can temporarily block and/or inactivate your account. Please note that you are responsible for your use of your account and may be liable for your account even if your account is misused by others.	
									</li><br>
									<li>
									BaiPaJoin has the full right to temporarily block, delete, or deactivate your account at our sole discretion and for any cause without giving reasons for blocking, deletion, or deactivation of your account provided that this will not affect our obligations to provide any Services that have been purchased by you prior to that time (unless we also cancel those Services as permitted by these Terms). The reasons for blocking, deletion, or deactivation of your account may include but are not limited to:
										<div class = "another_list2">
											<ol><br>
												<li>
													breach of these Terms, 
												</li><br>
												<li>
												prohibitions in law or regulations, 
												</li><br>
												<li>
												fraud or theft (or indication or suspicion of fraud or theft),  
												</li><br>
												<li>
												suspicion of criminal activity, 
												</li><br>
												<li>
												suspicious ordering,  
												</li><br>
												<li>
												you are providing inaccurate, erroneous or misleading information,
												</li><br>
												<li>
												your inappropriate behavior, threats, or insults,  
												</li><br>
												<li>
												refusal to provide information,  
												</li><br>
												<li>
												practical impediments,  
												</li><br>
												<li>
												communication difficulties or breakdowns, or  
												</li><br>
												<li>
												you are listed on any “black lists” or “watch lists” by governments or international organizations. 
												</li><br>
											</ol>
										</div>
								</ul>
							</p>
						</section>
						<section>
							<h2>PAYMENT DETAILS AND PROCEDURES</h2>
							<p>
								<ul>
									<li>
									Payments are made in the amount and currency as stated, not including bank fees or any other charges by BaiPaJoin and its payment gateway partner. 
									</li><br>
									<li>
									For all forms of booking reservations, you must make payment within the specified time limit. If the payment is not made, then BaiPaJoin reserves the right to cancel all reservations.
									</li><br>
								</ul>
							</p>
						</section>
						<section>
							<h2>PAYMENT BY CREDIT CARD AND FRAUD</h2>
							<p>
								<ul>
									<li>
									In the case of credit card fraud or unauthorized use of your credit card by a third party, you must contact your bank or card issuer immediately after realizing such unauthorized use. In such a case, BaiPaJoin has no responsibility over any case of credit card fraud or unauthorized use of your credit card by a third party, regardless of whether such fraud or unauthorized use was carried out through BaiPaJoin site or app, other than where this arose from the fraud, negligence or willful action of BaiPaJoin or as otherwise required by law. Except as otherwise provided by law, BaiPaJoin is not obliged to make refunds or repayments to you as a result of such fraud. As part of our goodwill, we may provide a form of compensation to you subject to review and approval by us. You shall only be eligible to request compensation only if such reservations have been made through our secure servers and the fraud or unauthorized use of your credit card is a result of our default or negligence and through no fault of your own while using the secure server or you are otherwise entitled by law to be compensated. We accept no liability of the fraud or unauthorized use of your credit card if it was done through applications or servers other than our own or if it is as a result of a fault or negligence of your own. If you suspect any unauthorized reservations or fraud committed on BaiPaJoin, you must contact BaiPaJoin Customer Service at teambaipajoincebu@gmail.com. 
									</li><br>
									<li>
									To make a reservation you must be aged over eighteen (18) years old and have full legal capacity to make the transaction (or have authorization from your legal guardian). You must use a credit or debit card that you own, issued in your name, and make sure that there are sufficient funds to cover the transaction costs. Any fees incurred by you for authorizing a transaction that results in an over-drawing of your account are not the responsibility of BaiPaJoin..
									</li><br>
									<li>
									You shall ensure that the details you provide to us are completely accurate. BaiPaJoin reserves the right to not accept certain credit cards. BaiPaJoin can add or remove other payment methods at our sole discretion.
									</li><br>
									<li>
									In certain cases, we may require additional information or verification to validate and confirm the booking, as described in more detail on the Site. Reservations are not confirmed until you have received a confirmation email with a transaction receipt or booking itinerary receipt and there is the possibility that the Organizer and/or Payment Gateway Partners can implement fraud examination mechanisms during the booking process. If fraud occurs or is determined to occur, then the booking will be void. BaiPaJoin shall not bear any responsibility for such cancellations by the Organizer. If you choose not to submit additional information, reservations will not be completed and will be voided.
								</ul>
							</p>
						</section>
						<section>
							<h2>RIGHTS TO USER CONTENT</h2>
							<p>
								<ul>
									<li>
									Users have the rights to submit a review which may contain any media (photo and/or video) in respect to the selection of Organizer. By doing so, you hereby agree to be bound by the terms and conditions as set out in this section.
									</li><br>
									<li>
									In providing a review which may or may not contain any media (photo and/or video) to the Site, you agree to ensure that:
										<div class = "another_list">
											<ul><br>
												<li>
													you own and control all of the rights to the reviews and its content that you provide to the Site;
												</li><br>
												<li>
													you grant us a perpetual, irrevocable, royalty-free, worldwide, sub-licensable and transferable license to use your reviews, including all intellectual property rights therein, in connection with our business operations including the operation and management of the Site;any losses, claims or legal consequences arising from the rescheduling and/or modifications and/or changes to your reservation made directly through the Vendors.
												</li><br>
												<li>
													the content of the review is accurate and contains no misrepresentations; and
												</li><br>
												<li>
													the use or performance or transmission of the content of the review does not violate these Terms or applicable laws and regulations, you are not violating any third party’s rights, and you are not causing injury to any party.
												</li><br>
											</ul>
										</div>
									</li><br>
									<li>
									You must bear all responsibility for the content of the reviews that you provide or submit.
									</li><br>
								</ul>
							</p>
						</section>





					</div>
				</div>
			</main>
		</div>

	<div class="clearfix"></div>
	</div>
</div>
<!-- End Main -->

<!--Footer -->
<?php include("includes/footer.php"); ?>
<!-- End Footer -->
