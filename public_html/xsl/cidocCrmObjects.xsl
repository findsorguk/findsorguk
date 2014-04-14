<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
                xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
                xmlns:owl="http://www.w3.org/2002/07/owl#"
                xmlns:crm="http://erlangen-crm.org/current/"
                xmlns:crmeh="http://purl.org/crmeh#"
                xmlns:crmbm="http://collection.britishmuseum.org/id/crm/bm-extensions/" 
                xmlns:claros="http://purl.org/NET/Claros/vocab#"
                xmlns:dc="http://purl.org/dc/elements/1.1/"
                xmlns:oac="http://www.openannotation.org/ns/"
                xmlns:dcterms="http://purl.org/dc/terms/"
				xmlns:skos="http://www.w3.org/2004/02/skos/core#" 
     			xmlns:google="http://rdf.data-vocabulary.org/#"
				xmlns:con="http://www.w3.org/2000/10/swap/pim/contact#"
				xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#"
				xmlns:units="http://qudt.org/"
				xmlns:og="http://ogp.me/ns#"
				xmlns:nm="http://nomisma.org/id/"
				xmlns:foaf="http://xmlns.com/foaf/0.1/"
				xmlns:cc="http://creativecommons.org/ns#"
				xmlns:gn="http://www.geonames.org/ontology#" 
				xmlns:osAdminGeo="http://data.ordnancesurvey.co.uk/ontology/admingeo/"
				xmlns:osSpatialRel="http://data.ordnancesurvey.co.uk/ontology/spatialrelations/"
				xmlns:j.0="http://purl.org/net/provenance/types#"
				xmlns:lawd="http://lawd.info/ontology/1.0/"
				xmlns:pas="http://finds.org.uk/ontology/"
				xml:lang="en"
                >
                
    <xsl:output method="xml" indent="yes" encoding="utf-8" />

	<xsl:param name="url">
		<xsl:value-of select="'http://finds.org.uk/database/artefacts/record/id/'" />
	</xsl:param> 
	
	<xsl:param name="denomUrl">
		<xsl:value-of select="'http://finds.org.uk/database/terminology/denominations/denomination/id/'" />
	</xsl:param>
	
	<xsl:param name="findsTerms">
		<xsl:value-of select="'http://finds.org.uk/database/terminology/'"/>
	</xsl:param>
	
	<xsl:param name="bmThes">
		<xsl:value-of select="'http://collection.britishmuseum.org/id/thesauri/'" />
	</xsl:param>
	
	<xsl:param name="thumb">
		<xsl:value-of select="'http://finds.org.uk/images/thumbnails/'" />
	</xsl:param>
	
	<xsl:param name="images">
		<xsl:value-of select="'http://finds.org.uk/images/'" />
	</xsl:param>
	
	<xsl:param name="nomismaUri">
		<xsl:value-of select="'http://nomisma.org/id/'" />
	</xsl:param>
	
	<xsl:param name="osUri">
		<xsl:value-of select="'http://data.ordnancesurvey.co.uk/id/70000000000'" />
	</xsl:param>
	
	<xsl:param name="pleiadesUri">
		<xsl:value-of select="'http://pleiades.stoa.org/places/'"/>
	</xsl:param>
	
	<xsl:param name="dbpediaUri">
		<xsl:value-of select="'http://dbpedia.org/page/'"/>
	</xsl:param>
	
	<xsl:param name="language">
		<xsl:value-of select="'en'" />
	</xsl:param> 
	
	<xsl:template match="/">
		<rdf:RDF>
			<xsl:apply-templates select="//artefact" />
		</rdf:RDF>
	</xsl:template>
	
	
	<xsl:template match="//artefact">
	<rdf:Description>
	<xsl:attribute name="rdf:about">http://finds.org.uk</xsl:attribute>
	<j.0:DataCreatingService>
	<xsl:attribute name="xsd:string">The Portable Antiquities Scheme/ The British Museum</xsl:attribute>
	</j.0:DataCreatingService>
	<dcterms:title>
	<xsl:attribute name="xsd:string">Portable Antiquities Scheme linked data</xsl:attribute>
	</dcterms:title>
	<dcterms:description>
	<xsl:attribute name="xsd:string">The Portable Antiquities Scheme is a DCMS funded project to encourage the voluntary recording of archaeological objects found by members of the public in England and Wales. Every year many thousands of objects are discovered, many of these by metal-detector users, but also by people whilst out walking, gardening or going about their daily work. Such discoveries offer an important source for understanding our past.</xsl:attribute>
	</dcterms:description>
	<dcterms:coverage>
	<xsl:attribute name="rdf:resource">http://data.ordnancesurvey.co.uk/doc/country/england</xsl:attribute>
	</dcterms:coverage>
	<dcterms:coverage>
	<xsl:attribute name="rdf:resource">http://data.ordnancesurvey.co.uk/doc/country/wales</xsl:attribute>
	</dcterms:coverage>
	</rdf:Description>
	
	
	
	<foaf:Document>
	<xsl:attribute name="rdf:about"><xsl:value-of select="$url"/><xsl:value-of select="id"/></xsl:attribute> 
		
		
		<dcterms:contributor>
			<foaf:Person>
				<rdfs:label>
					<xsl:attribute name="xsd:string">Created by: <xsl:value-of select="creator"/></xsl:attribute>
				</rdfs:label>
				<foaf:name>
					<xsl:attribute name="xsd:string"><xsl:value-of select="creator"/></xsl:attribute>
				</foaf:name>
			</foaf:Person>
		</dcterms:contributor>
		
		<xsl:if test="updatedBy">
		<dcterms:contributor>
			<foaf:Person>
				<rdfs:label>
					<xsl:attribute name="xsd:string">Updated by: <xsl:value-of select="updatedBy"/></xsl:attribute>
				</rdfs:label>
				<foaf:name>
					<xsl:attribute name="xsd:string"><xsl:value-of select="updatedBy"/></xsl:attribute>
				</foaf:name>
			</foaf:Person>
		</dcterms:contributor>
		</xsl:if>
		
		<xsl:if test="identifier">
		<dcterms:contributor>
			<foaf:Person>
				<rdfs:label>
					<xsl:attribute name="xsd:string">Identified by: <xsl:value-of select="identifier"/></xsl:attribute>
				</rdfs:label>
				<foaf:name>
					<xsl:attribute name="xsd:string"><xsl:value-of select="identifier"/></xsl:attribute>
				</foaf:name>
			</foaf:Person>
		</dcterms:contributor>
		</xsl:if>
		
		<xsl:if test="secondaryIdentifier">
		<dcterms:contributor>
			<foaf:Person>
				<rdfs:label>
					<xsl:attribute name="xsd:string">Secondary identifier: <xsl:value-of select="secondaryIdentifier"/></xsl:attribute>
				</rdfs:label>
				<foaf:name>
					<xsl:attribute name="xsd:string"><xsl:value-of select="secondaryIdentifier"/></xsl:attribute>
				</foaf:name>
			</foaf:Person>
		</dcterms:contributor>
		</xsl:if>
		
		<xsl:if test="description">
		<dcterms:description>
			<xsl:attribute name="xsd:string"><xsl:value-of select="description"/></xsl:attribute>
		</dcterms:description>
		</xsl:if>
		
		<xsl:if test="updated">
		<dcterms:modified>
			<xsl:attribute name="xsd:date"><xsl:value-of select="updated"/></xsl:attribute>
		</dcterms:modified>
		</xsl:if>
		
		<dcterms:created>
			<xsl:attribute name="xsd:date"><xsl:value-of select="created"/></xsl:attribute>
		</dcterms:created>
		
		<dcterms:coverage>
        	<xsl:attribute name="xsd:string">England and Wales</xsl:attribute>
		</dcterms:coverage>
		
		<dcterms:publisher>
			<xsl:attribute name="xsd:string">Portable Antiquities Scheme/ The British Museum</xsl:attribute>
		</dcterms:publisher>
		
		<cc:attributionName>
			<xsl:attribute name="xsd:string">The Portable Antiquities Scheme and the British Museum</xsl:attribute>
		</cc:attributionName>
		
		<cc:attributionURL>
			<xsl:attribute name="rdf:resource">http://finds.org.uk</xsl:attribute>
		</cc:attributionURL>
		
		<cc:license>
			<rdf:Description>
			<xsl:attribute name="rdf:about">http://creativecommons.org/licenses/by-sa/3.0/</xsl:attribute>
				<cc:permits>
					<xsl:attribute name="rdf:resource">http://web.resource.org/cc/Reproduction</xsl:attribute>
				</cc:permits>
				<cc:permits>
					<xsl:attribute name="rdf:resource">http://web.resource.org/cc/Distribution</xsl:attribute>
				</cc:permits>
				<cc:permits>
					<xsl:attribute name="rdf:resource">http://web.resource.org/cc/DerivativeWorks</xsl:attribute>
				</cc:permits>
				<cc:permits>
					<xsl:attribute name="rdf:resource">http://web.resource.org/cc/CommercialUse</xsl:attribute>
				</cc:permits>
				<cc:requires>
					<xsl:attribute name="rdf:resource">http://web.resource.org/cc/Attribution</xsl:attribute>
				</cc:requires>
				<cc:requires>
					<xsl:attribute name="rdf:resource">http://web.resource.org/cc/Notice</xsl:attribute>
				</cc:requires>
				<cc:requires>
					<xsl:attribute name="rdf:resource">http://web.resource.org/cc/ShareAlike</xsl:attribute>
				</cc:requires>
				<rdfs:label>
					<xsl:attribute name="xsd:string">By Attribution Share-Alike 3.0</xsl:attribute>
				</rdfs:label>
			</rdf:Description>
		</cc:license>	
		
	<rdfs:label>RDF description of <xsl:value-of select="old_findID"/></rdfs:label>
		
	<foaf:primaryTopic>

		<crm:E22_Man-Made_Object>
			
			<!--  The object's title -->
			<crm:P102_has_title>
			    <crm:E35_Title>
			      <xsl:attribute name="xsd:string">Object record for: <xsl:value-of select="old_findID"/></xsl:attribute>
			    </crm:E35_Title>
			</crm:P102_has_title>
			
			<!-- The preferred identifier: Must exist only once--> 
			<crm:P48_has_preferred_identifier>
				<crm:E42_Identifier> 
					<xsl:attribute name="xsd:string"><xsl:value-of select="old_findID"/></xsl:attribute>
				</crm:E42_Identifier>
			</crm:P48_has_preferred_identifier>
			
			<!--  Identifiers  -->
			
			<!--  internal UID  -->
			<crm:P1_is_identified_by>
				<crm:E42_Identifier>
					<xsl:attribute name="xsd:string"><xsl:value-of select="secuid"/></xsl:attribute>
					<rdfs:label rdf:datatype="xsd:string">Secure ID: <xsl:value-of select="secuid"/></rdfs:label>
				</crm:E42_Identifier>
			</crm:P1_is_identified_by>
			
			<!--  The integer used for constructing uris -->
			<crm:P1_is_identified_by>
				<crm:E42_Identifier>
					<xsl:attribute name="xsd:integer"><xsl:value-of select="id"/></xsl:attribute>
					<rdfs:label rdf:datatype="xsd:string">Url string integer ID: <xsl:value-of select="id"/></rdfs:label>
				</crm:E42_Identifier>
			</crm:P1_is_identified_by>
			
			<!--  The other ref that might be applied -->
			<xsl:if test="otherRef">
			<crm:P1_is_identified_by>
				<crm:E42_Identifier>
					<xsl:attribute name="xsd:string"><xsl:value-of select="otherRef" /></xsl:attribute>
					<crm:P2_has_type>
					<xsl:attribute name="rdf:resource"><xsl:value-of select="$bmThes"/>thesauri/identifier/otherid</xsl:attribute>
					</crm:P2_has_type>
					<rdfs:label rdf:datatype="xsd:string">Other reference number: <xsl:value-of select="otherRef" /></rdfs:label>
				</crm:E42_Identifier>
			</crm:P1_is_identified_by>
			<crmbm:reg_id>
				<xsl:value-of select="otherRef"/>
			</crmbm:reg_id>
			</xsl:if>
		
			
			<!--  The treasure case number -->
			<xsl:if test="TID">
			<crm:P1_is_identified_by>
				<crm:E42_Identifier>
					<xsl:attribute name="xsd:string"><xsl:value-of select="TID" /></xsl:attribute>
					<rdfs:label rdf:datatype="xsd:string">Treasure reference number: <xsl:value-of select="TID" /></rdfs:label>
				</crm:E42_Identifier>
			</crm:P1_is_identified_by>
			<crmbm:other_id>
				<xsl:value-of select="treasureID"/>
			</crmbm:other_id>
			</xsl:if>
			
			<!--  Where documented -->
			<crm:P70i_is_documented_in>
				<crm:E32_Authority_Document>
					<crm:P48_has_preferred_identifier>
			  			<crm:E42_Identifier>
			  			<xsl:attribute name="rdf:about"><xsl:value-of select="$url"/><xsl:value-of select="id"/>/format/rdf</xsl:attribute>
			  			</crm:E42_Identifier> 
			  		</crm:P48_has_preferred_identifier>
				</crm:E32_Authority_Document>
			</crm:P70i_is_documented_in>
			
			<!-- Rights of use -->
			<crm:P104_is_subject_to >
				<crm:E30_Right>
					<xsl:attribute name="rdf:about">http://creativecommons.org/licenses/by-sa/3.0/</xsl:attribute>
					<crm:P3_has_note><xsl:attribute name="xsd:string">Copyright the Portable Antiquities Scheme/British Museum</xsl:attribute></crm:P3_has_note>
				</crm:E30_Right>
			</crm:P104_is_subject_to>
			
			<!--  object type appellation -->
			<crmbm:PX_object_type>
			<xsl:attribute name="rdf:resource">http://finds.org.uk/database/terminology/object/term/<xsl:value-of select="objecttype"/></xsl:attribute> 
			</crmbm:PX_object_type>
			
			<crm:P2_has_type>
				<crm:E55_Type>
					<xsl:attribute name="xsd:string"><xsl:value-of select="objecttype"/></xsl:attribute>
					<rdfs:label>
						<xsl:attribute name="xsd:string">Object type: <xsl:value-of select="objecttype"/></xsl:attribute>
					</rdfs:label>
					<skos:inScheme><xsl:value-of select="$bmThes"/>object</skos:inScheme>
						<owl:sameAs>
							<xsl:attribute name="rdf:resource">http://finds.org.uk/database/terminology/object/term/<xsl:value-of select="objecttype"/></xsl:attribute> 
						</owl:sameAs>
				</crm:E55_Type>
			</crm:P2_has_type>
			
			<!-- Description of the object -->
			<crmbm:PX.curatorial_comment><xsl:attribute name="xsd:string"><xsl:value-of select="description"/></xsl:attribute></crmbm:PX.curatorial_comment>
			
			<!-- Dating of the object -->
			<!--  Timespan -->
			<crm:P4_has_time-span>
				<crm:E52_Time-Span>
					<crm:P82a_begin_of_the_begin rdf:datatype="xsd:gYear"><xsl:value-of select="fromdate" /></crm:P82a_begin_of_the_begin>
					<xsl:if test="todate">
					<crm:P82b_end_of_the_end rdf:datatype="xsd:gYear"><xsl:value-of select="todate" /></crm:P82b_end_of_the_end>
					</xsl:if>
					
					<crm:P3_has_note rdf:datatype="xsd:string"><xsl:value-of select="fromdate" /><xsl:if test="todate"> - <xsl:value-of select="todate" /></xsl:if></crm:P3_has_note>
					<rdfs:label rdf:datatype="xsd:string">Date range for object: <xsl:value-of select="fromdate" /><xsl:if test="todate">  - <xsl:value-of select="todate" /></xsl:if></rdfs:label>
					<crm:P82_at_some_time_within>
						<crm:E61_Time_Primitive>
							<claros:not_before rdf:datatype="xsd:gyear"><xsl:value-of select="fromdate" /></claros:not_before>
							<xsl:if test="todate"> 
							<claros:not_after rdf:datatype="xsd:gyear"><xsl:value-of select="todate" /></claros:not_after>
							</xsl:if>
						</crm:E61_Time_Primitive>
					</crm:P82_at_some_time_within>
				</crm:E52_Time-Span>
			</crm:P4_has_time-span>
			
			<!-- Period mapping (broadperiod) -->
			<crm:P108i_was_produced_by>
				
				<crm:E12_Production>
					<crm:P10_falls_within>
						<crm:E4_Period>
							<rdfs:label>
								<xsl:attribute name="xsd:string">The broadperiod of the object is: <xsl:value-of select="broadperiod"/></xsl:attribute>
							</rdfs:label>
							<crm:E49_Time_Appellation>
								<xsl:attribute name="xsd:string"><xsl:value-of select="broadperiod"/></xsl:attribute>
							</crm:E49_Time_Appellation>
						</crm:E4_Period>
					</crm:P10_falls_within>
				</crm:E12_Production>
			</crm:P108i_was_produced_by>
			
			<crm:P108i_was_produced_by>
				<crm:E12_Production>
					<crm:P10_falls_within>
						<crm:E4_Period>
							<rdfs:label>
								<xsl:attribute name="xsd:string">The starting period of the object is: <xsl:value-of select="periodFromName"/></xsl:attribute>
							</rdfs:label>
							<crm:E49_Time_Appellation>
								<xsl:attribute name="xsd:string"><xsl:value-of select="periodFromName"/></xsl:attribute>
							</crm:E49_Time_Appellation>
						</crm:E4_Period>
					</crm:P10_falls_within>
				</crm:E12_Production>
			</crm:P108i_was_produced_by>
			
			<crm:P108i_was_produced_by>
				<crm:E12_Production>
					<crm:P10_falls_within>
						<crm:E4_Period>
							<rdfs:label>
								<xsl:attribute name="xsd:string">The end period of the object is: <xsl:value-of select="periodToName"/></xsl:attribute>
							</rdfs:label>
							<crm:E49_Time_Appellation>
								<xsl:attribute name="xsd:string"><xsl:value-of select="periodToName"/></xsl:attribute>
							</crm:E49_Time_Appellation>
						</crm:E4_Period>
					</crm:P10_falls_within>
				</crm:E12_Production>
			</crm:P108i_was_produced_by>
			
			
			<!--  Part of the PAS collection -->
			<crm:P46i_forms_part_of>
				<crm:E78_Collection>
					<crm:P53_has_former_or_current_location>
						<crm:E53_Place>
							<crm:P1_is_identified_by>
								<crm:E48_Place_Name><xsl:attribute name="xsd:string">The Portable Antiquities Scheme database</xsl:attribute></crm:E48_Place_Name> 
							</crm:P1_is_identified_by>
						</crm:E53_Place>
					</crm:P53_has_former_or_current_location>
				</crm:E78_Collection>
			</crm:P46i_forms_part_of>
			
			<!--  Dimensions and weights -->
			
			<!--  Width -->
			<xsl:if test="width">
			<crm:P43_has_dimension>
	            <crm:E54_Dimension>
	            <xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>dimension/width</xsl:attribute> 
	                <crm:P91_has_unit rdf:resource="http://qudt.org/vocab/unit#Millimeter" />
	                    <crm:P90_has_value>
	                        <xsl:attribute name="xsd:double"><xsl:value-of select="width"/></xsl:attribute> 
	                    </crm:P90_has_value>
	                <rdfs:label><xsl:attribute name="xsd:string">Width: <xsl:value-of select="width"/> mm</xsl:attribute></rdfs:label>
	                <crm:P3_has_note><xsl:attribute name="xsd:string"><xsl:value-of select="width"/></xsl:attribute></crm:P3_has_note>
	            </crm:E54_Dimension>
	        </crm:P43_has_dimension>
         	</xsl:if>
                    
			<!--  Diameter  -->
			<xsl:if test="diameter">
			<crm:P43_has_dimension>
            	<crm:E54_Dimension>
        			<crm:P2_has_type>
            			<xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>dimension/diameter</xsl:attribute>
            		</crm:P2_has_type> 
            		<crm:P91_has_unit rdf:resource="http://qudt.org/vocab/unit#Millimeter" />
            		<crm:P90_has_value>
                		<xsl:attribute name="xsd:double"><xsl:value-of select="diameter"/></xsl:attribute> 
            		</crm:P90_has_value>
            		<crm:P3_has_note><xsl:attribute name="xsd:string"><xsl:value-of select="diameter"/></xsl:attribute></crm:P3_has_note>
            		<rdfs:label><xsl:attribute name="xsd:string">Diameter: <xsl:value-of select="diameter"/> mm</xsl:attribute></rdfs:label>
        		</crm:E54_Dimension>
             </crm:P43_has_dimension>
        	</xsl:if>         
		
			<!--  Height  -->
			<xsl:if test="height">
			<crm:P43_has_dimension>
                <crm:E54_Dimension>
                 <crm:P2_has_type>
                 <xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>dimension/height</xsl:attribute>
                 </crm:P2_has_type> 
                    <crm:P91_has_unit rdf:resource="http://qudt.org/vocab/unit#Millimeter"/>
                    <crm:P90_has_value>
                        <xsl:attribute name="xsd:double"><xsl:value-of select="height"/></xsl:attribute> 
                    </crm:P90_has_value>
                    <crm:P3_has_note><xsl:value-of select="height"/></crm:P3_has_note>
                    <rdfs:label>Height: <xsl:value-of select="height"/> mm</rdfs:label>
                </crm:E54_Dimension>
            </crm:P43_has_dimension>
       	 </xsl:if>
        
		<!--  Thickness  -->
		<xsl:if test="thickness">
			<crm:P43_has_dimension>
                <crm:E54_Dimension>
                	<crm:P2_has_type>
                	  <xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>dimension/thickness</xsl:attribute>
                	</crm:P2_has_type>
                    <crm:P91_has_unit rdf:resource="http://qudt.org/vocab/unit#Millimeter" />
                    <crm:P90_has_value>
                        <xsl:attribute name="xsd:double"><xsl:value-of select="thickness"/></xsl:attribute> 
                    </crm:P90_has_value>
                    <crm:P3_has_note>
                    	<xsl:attribute name="xsd:string"><xsl:value-of select="thickness"/></xsl:attribute>
                    </crm:P3_has_note>
                    <rdfs:label>Thickness: <xsl:value-of select="thickness"/> mm</rdfs:label>
                </crm:E54_Dimension>
            </crm:P43_has_dimension>
         </xsl:if>   
                         
         <!--  Weight  -->
		<xsl:if test="weight">
		<crm:P43_has_dimension>
	        <crm:E54_Dimension>
	        	<crm:P2_has_type> 
	        	<xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>dimension/weight</xsl:attribute>
	       	</crm:P2_has_type>
	            <crm:P91_has_unit rdf:resource="http://qudt.org/vocab/unit#Gram" />
	            <crm:P90_has_value>
	                	<xsl:attribute name="xsd:decimal"><xsl:value-of select="weight"/></xsl:attribute> 
	            </crm:P90_has_value>
	            <rdfs:label>
	            	<xsl:attribute name="xsd:string">Weight: <xsl:value-of select="weight"/> grammes</xsl:attribute>
	            </rdfs:label>
	        </crm:E54_Dimension>
        </crm:P43_has_dimension>   
		</xsl:if>
		
		<crm:P43_has_dimension>
			<crm:E54_Dimension>
				<crm:P2_has_type> 
		        	<xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>dimension/quantity</xsl:attribute>
		       	</crm:P2_has_type>
				<crm:P90_has_value>
					<xsl:attribute name="xsd:integer"><xsl:value-of select="quantity"/></xsl:attribute>
				</crm:P90_has_value>
				 <rdfs:label>
	            	<xsl:attribute name="xsd:string">Quantity: <xsl:value-of select="quantity"/></xsl:attribute>
	            </rdfs:label>
			</crm:E54_Dimension>
		</crm:P43_has_dimension>
		
		<!-- Production techniques -->
		
		<crm:P108i_was_produced_by>
			<crm:P32_used_general_technique>
			</crm:P32_used_general_technique>
		</crm:P108i_was_produced_by>
		
		<!--  the current location -->
		<xsl:if test="currentLocation">
		<crm:P55F_has_current_location>
	         <crm:P87_is_identified_by>
	             <crm:E53_Place>
	             	<crm:P1_is_identified_by>
	             		<crm:E48_Place_Name>
	             			<xsl:attribute name="xsd:string"><xsl:value-of select="currentLocation"/></xsl:attribute>
	             		</crm:E48_Place_Name>
	                 </crm:P1_is_identified_by>
	             </crm:E53_Place>
	         </crm:P87_is_identified_by>
		</crm:P55F_has_current_location>
		</xsl:if>
		
		<!--  Image representations -->
			<xsl:if test="thumbnail">
			<!--  Thumbnail -->
			<crm:P138i_has_representation>
				<crm:E38_Image>
				<xsl:attribute name="rdf:about"><xsl:value-of select="$thumb"/><xsl:value-of select="thumbnail"/>.jpg</xsl:attribute> 
					<rdfs:label>
						<xsl:attribute name="xsd:string">A thumbnail image of <xsl:value-of select="old_findID"/></xsl:attribute>
					</rdfs:label>
					<crm:P2_has_type rdf:resource="http://purl.org/NET/Claros/vocab#Thumbnail" />
				</crm:E38_Image>
			</crm:P138i_has_representation>
	
			<xsl:if test="filename">
			<crm:P138i_has_representation>
				<crm:E38_Image>
				<xsl:attribute name="rdf:about"><xsl:value-of select="$images"/><xsl:value-of select="imagedir"/><xsl:value-of select="filename"/></xsl:attribute> 
					<rdfs:label>
						<xsl:attribute name="xsd:string">A fullsized image of <xsl:value-of select="old_findID"/></xsl:attribute>
					</rdfs:label>
				</crm:E38_Image>
			</crm:P138i_has_representation>
			
			<!--  BM extension for main image of object -->
			<crmbm:PX_has_main_representation>
			<xsl:attribute name="rdf:resource"><xsl:value-of select="$images"/><xsl:value-of select="imagedir"/><xsl:value-of select="filename"/></xsl:attribute>
			</crmbm:PX_has_main_representation>
			
			</xsl:if> <!-- end of test for filename -->
				
			</xsl:if>	<!--  end of test for end of thumbnail element -->
			
			

			<!--  The discovery data -->
			<crm:P12i_was_present_at>
				<crmbm:EX_discovery>
					<rdfs:label>
						<xsl:attribute name="xsd:string">Discovered or excavated</xsl:attribute>
					</rdfs:label>
					<crm:P2_has_type>
						<xsl:attribute name="rdf:resource"><xsl:value-of select="$bmThes"/>id/thesauri/find/E</xsl:attribute>
					</crm:P2_has_type>
					<crm:P2_has_type rdf:resource="http://purl.org/NET/Claros/vocab#coordinates-find" />
					<xsl:if test="datefound1">
					<crmbm:PX.time-span_earliest><xsl:attribute name="xsd:dateTime"><xsl:value-of select="datefound1"/></xsl:attribute></crmbm:PX.time-span_earliest>
					</xsl:if>
					<xsl:if test="datefound2">
					<crmbm:PX.time-span_latest><xsl:attribute name="xsd:dateTime"><xsl:value-of select="datefound2"/></xsl:attribute></crmbm:PX.time-span_latest>
					</xsl:if>
					<crm:P7_took_place_at>
						<xsl:if test="knownas">
						<pas:knownas><xsl:attribute name="xsd:string"><xsl:value-of select="knownas"/></xsl:attribute></pas:knownas>
						</xsl:if>
						<!--  A known as test to hide parish and coordinates -->
						<xsl:if test="not(knownas)">
						<!--  EH extensions for coordinates -->
						<crm:E47_Spatial_Coordinates>
							<xsl:if test="elevation">
							<crmeh:EXP5.spatial_z>
								<xsl:attribute name="xsd:float"><xsl:value-of select="elevation" /></xsl:attribute>
							</crmeh:EXP5.spatial_z>	
							<rdf:value>
								<xsl:attribute name="xsd:string"><xsl:value-of select="fourFigureLat"/>,<xsl:value-of select="fourFigureLon"/>,<xsl:value-of select="elevation"/></xsl:attribute>
							</rdf:value>						
							</xsl:if>
							<crmeh:EXP5.spatial_x>
								<xsl:attribute name="xsd:decimal"><xsl:value-of select="fourFigureLat" /></xsl:attribute>
							</crmeh:EXP5.spatial_x>
							<crmeh:EXP5.spatial_y>
								<xsl:attribute name="xsd:decimal"><xsl:value-of select="fourFigureLon" /></xsl:attribute>
							</crmeh:EXP5.spatial_y>
							<!--  Claros style geo object -->
							<claros:has_geoObject>
			        			<geo:Point>
						          <geo:lat rdf:datatype="xsd:decimal"><xsl:value-of select="fourFigureLat"/></geo:lat>
						          <geo:long rdf:datatype="xsd:decimal"><xsl:value-of select="fourFigureLon"/></geo:long>
						        </geo:Point>
			      			</claros:has_geoObject>
						</crm:E47_Spatial_Coordinates>
						</xsl:if>
					</crm:P7_took_place_at>
					<lawd:foundAt>
			      				<crmeh:EHE0002_ArchaeologicalSite>
									<rdfs:label><xsl:value-of select="county"/></rdfs:label>
								</crmeh:EHE0002_ArchaeologicalSite>
			      			</lawd:foundAt>
			      	<xsl:if test="precision">
			      	<pas:coordinatePrecision>
			      		<rdf:Description>
				      		<rdf:value><xsl:attribute name="xsd:integer"><xsl:value-of select="precision"/></xsl:attribute></rdf:value>
				      		<rdfs:label>
				      			<xsl:attribute name="xsd:string">Grid reference precision to: <xsl:value-of select="precision"/> figures</xsl:attribute>
				      		</rdfs:label>
			      		</rdf:Description>
			      	</pas:coordinatePrecision>
			      	</xsl:if>
			      	<xsl:if test="accuracy">
			      	<pas:accuracy>
						<rdf:Description>
							<rdf:value><xsl:attribute name="xsd:integer"><xsl:value-of select="accuracy"/></xsl:attribute></rdf:value>
							<rdfs:label>
								<xsl:attribute name="xsd:string">Coordinates place object within a <xsl:value-of select="precision"/> metre square</xsl:attribute>
							</rdfs:label>
						</rdf:Description>
					</pas:accuracy>
					</xsl:if>
					<xsl:if test="regionID">
				      	<osAdminGeo:inEuropeanRegion>
					    <xsl:attribute name="rdf:about"><xsl:value-of select="regionID"/></xsl:attribute>
					    </osAdminGeo:inEuropeanRegion>
					    </xsl:if>
					    <xsl:if test="countyID">
					    <osSpatialRel:within>
					    <xsl:attribute name="rdf:about"><xsl:value-of select="$osUri"/><xsl:value-of select="countyID"/></xsl:attribute>
					    </osSpatialRel:within>
					    </xsl:if>
					    <xsl:if test="districtID">
					    <osAdminGeo:inDistrict>
					    <xsl:attribute name="rdf:about"><xsl:value-of select="$osUri"/><xsl:value-of select="districtID"/></xsl:attribute>
					    </osAdminGeo:inDistrict>
					    </xsl:if>
					    <xsl:if test="county">
					    <osAdminGeo:county>
					      	<rdf:Description>
					      	<xsl:attribute name="rdf:about"><xsl:value-of select="$osUri"/><xsl:value-of select="countyID"/></xsl:attribute>
					  			<rdfs:label>
					  				<xsl:attribute name="xsd:string"><xsl:value-of select="county"/></xsl:attribute>
					  			</rdfs:label>
					    	</rdf:Description>
					    </osAdminGeo:county>
					    </xsl:if>
					    <xsl:if test="district">
						<osAdminGeo:district>
							<rdf:Description>
						      	<xsl:attribute name="rdf:about"><xsl:value-of select="$osUri"/><xsl:value-of select="districtID"/></xsl:attribute>
						  			<rdfs:label>
						  				<xsl:attribute name="xsd:string"><xsl:value-of select="district"/></xsl:attribute>
						  			</rdfs:label>
						  	</rdf:Description>
						</osAdminGeo:district>
						</xsl:if>
						<xsl:if test="parish">
						<osAdminGeo:parish>
							<rdf:Description>
						      	<xsl:attribute name="rdf:about"><xsl:value-of select="$osUri"/><xsl:value-of select="parishID"/></xsl:attribute>
						  			<rdfs:label>
						  				<xsl:attribute name="xsd:string"><xsl:value-of select="parish"/></xsl:attribute>
						  			</rdfs:label>
						  	</rdf:Description>
						</osAdminGeo:parish>
						</xsl:if>
				</crmbm:EX_discovery>
			</crm:P12i_was_present_at>
			
			
			<xsl:if test="objecttype = 'COIN'">
			<!--  The denomination -->
			<crm:P43_has_dimension>
				<crm:E54_Dimension>
					<rdfs:label>Unit of currency</rdfs:label>
					<crm:P2_has_type>
						<xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes" />dimension/currency</xsl:attribute>
					</crm:P2_has_type>
					<crm:P91_has_unit>
						<xsl:attribute name="rdf:resource"><xsl:value-of select="$denomUrl"/><xsl:value-of select="denomination"/></xsl:attribute>
					</crm:P91_has_unit> 
					<rdfs:label rdf:datatype="xsd:string"><xsl:value-of select="denominationName"/></rdfs:label>
					<xsl:if test="nomismaDenomination">
					<owl:sameAs>
						<xsl:attribute name="rdf:resource"><xsl:value-of select="$nomismaUri"/><xsl:value-of select="nomismaDenomination"/></xsl:attribute>
					</owl:sameAs>
					</xsl:if>
					<xsl:if test="bmDenomination">
					<owl:sameAs>
						<xsl:attribute name="rdf:resource"><xsl:value-of select="$bmThes" />currency/<xsl:value-of select="bmDenomination"/></xsl:attribute>
					</owl:sameAs>
					</xsl:if>
					<xsl:if test="dbpediaDenomination">
					<owl:sameAs>
						<xsl:attribute name="rdf:resource"><xsl:value-of select="$dbpediaUri" /><xsl:value-of select="dbpediaDenomination"/></xsl:attribute>
					</owl:sameAs>
					</xsl:if>
					<crm:P3_has_note>Portable Antiquities currency term: <xsl:value-of select="denominationName"/></crm:P3_has_note>
				</crm:E54_Dimension>
            </crm:P43_has_dimension>
            
            <!--  The mint -->
            <xsl:if test="mint">
			<crmbm:PX.minted_in>
				<xsl:attribute name="rdf:resource">http://finds.org.uk/terminology/mints/mint/id/<xsl:value-of select="mint"/></xsl:attribute>
			</crmbm:PX.minted_in>
			
			<crm:P108i_was_produced_by>
    			<crm:E12_Production>
      				<rdfs:label>Minted at: <xsl:value-of select="mintName"/></rdfs:label>
      				<crm:E53_Place>
						  <crm:P87_is_identified_by>
						    <crm:E48_Place_Name>
						      <xsl:attribute name="xsd:string"><xsl:value-of select="mintName"/></xsl:attribute>
						    </crm:E48_Place_Name>
						  </crm:P87_is_identified_by>
						</crm:E53_Place>
						<crm:P2_has_type>
							<xsl:attribute name="rdf:resource"><xsl:value-of select="$bmThes"/></xsl:attribute>
						</crm:P2_has_type>
      			</crm:E12_Production>
      		</crm:P108i_was_produced_by>
			</xsl:if>
			
			
			<!--  Representation of the ruler 	-->
			<xsl:if test="ruler">
			<crm:P138_represents>
				<crm:E21_Person>
					<crmbm:PX_profession>
					<xsl:attribute name="rdf:resource"><xsl:value-of select="$bmThes"/>id/thesauri/profession/ruler</xsl:attribute>
					</crmbm:PX_profession>
					<xsl:if test="broadperiod = ROMAN">
					<crm:P107i_is_current_or_former_member_of>
						<xsl:attribute name="rdf:resource"><xsl:value-of select="$bmThes"/>id/nationality/Roman</xsl:attribute>
					</crm:P107i_is_current_or_former_member_of>
					</xsl:if>
					<rdf:type>
						<xsl:attribute name="rdf:resource">http://erlangen-crm.org/current/E39_Actor</xsl:attribute>
					</rdf:type>
					<skos:prefLabel>
						<xsl:attribute name="xsd:string"><xsl:value-of select="rulerName"/></xsl:attribute>
					</skos:prefLabel>
					<rdf:type>
						<xsl:attribute name="rdf:resource">http://www.w3.org/2004/02/skos/core#Concept</xsl:attribute>
					</rdf:type>
					<crm:P131_is_identified_by>
						<crm:E82_Actor_Appellation>
							<rdfs:label><xsl:value-of select="rulerName"/></rdfs:label>
						</crm:E82_Actor_Appellation>
					</crm:P131_is_identified_by>
				</crm:E21_Person>
			</crm:P138_represents>
			</xsl:if>
			
			<!-- Moneyer -->
			<xsl:if test="moneyerName">
			<crm:P17_was_motivated_by>
				<crm:E21_Person>
					<crm:P131_is_identified_by>
						<crm:E82_Actor_Appellation>
							<rdfs:label><xsl:value-of select="moneyerName"/></rdfs:label>
						</crm:E82_Actor_Appellation>
					</crm:P131_is_identified_by>
					<rdf:type>
						<xsl:attribute name="rdf:resource">http://www.w3.org/2004/02/skos/core#Concept</xsl:attribute>
					</rdf:type>
					<rdf:type>
						<xsl:attribute name="rdf:resource">http://erlangen-crm.org/current/E39_Actor</xsl:attribute>
					</rdf:type>
					<crm:P3_has_note>Moneyer</crm:P3_has_note>
					<xsl:if test="broadperiod = ROMAN">
					<crm:P107i_is_current_or_former_member_of>
						<xsl:attribute name="rdf:resource"><xsl:value-of select="$bmThes"/>id/nationality/Roman</xsl:attribute>
					</crm:P107i_is_current_or_former_member_of>
					</xsl:if>
				</crm:E21_Person>
			</crm:P17_was_motivated_by>
			</xsl:if>
			
			<!-- Tribe IA only -->
			<xsl:if test="tribe">
			<crm:P17_was_motivated_by>
				<crm:E74_Group>
					<crm:P131_is_identified_by>
						<crm:E82_Actor_Appellation>
							<rdfs:label><xsl:value-of select="tribeName"/></rdfs:label>
						</crm:E82_Actor_Appellation>
					</crm:P131_is_identified_by>
					<rdf:type>
						<xsl:attribute name="rdf:resource">http://www.w3.org/2004/02/skos/core#Concept</xsl:attribute>
					</rdf:type>
					<rdf:type>
						<xsl:attribute name="rdf:resource">http://erlangen-crm.org/current/E39_Actor</xsl:attribute>
					</rdf:type>
					<crm:P3_has_note>Iron Age Tribe</crm:P3_has_note>
				</crm:E74_Group>
			</crm:P17_was_motivated_by>
			</xsl:if> 
			
			<!--  The obverse -->
			<xsl:if test="obverseDescription">
			<crm:P56_bears_feature>
				<crm:E25_Man-Made_Feature>
				<crm:P2_has_type>
				<xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>aspect/obverse</xsl:attribute>
				</crm:P2_has_type>
				<rdfs:label>Obverse description</rdfs:label>
				<crmbm:PX.physical_description><xsl:value-of select="obverseDescription"/></crmbm:PX.physical_description>
				<owl:sameAs>
					<xsl:attribute name="rdf:resource"><xsl:value-of select="$nomismaUri" />obverse</xsl:attribute>
				</owl:sameAs>
				</crm:E25_Man-Made_Feature>      
			</crm:P56_bears_feature>
			</xsl:if>
			
			<xsl:if test="obverseLegend">
			<crm:P56_bears_feature>
				<crm:E25_Man-Made_Feature>
                    <crm:P2_has_type>
                        <xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>association/namedInscription</xsl:attribute>
                    </crm:P2_has_type>
                    <rdfs:label>Obverse legend</rdfs:label>
                    <crmbm:PX.physical_description><xsl:attribute name="xsd:string"><xsl:value-of select="obverseLegend"/></xsl:attribute></crmbm:PX.physical_description>
                    <owl:sameAs>
						<xsl:attribute name="rdf:resource"><xsl:value-of select="$nomismaUri" />obverse</xsl:attribute>
                    </owl:sameAs>
				</crm:E25_Man-Made_Feature>      
			</crm:P56_bears_feature>
			</xsl:if>
			
			<!--  Reverse -->
			<xsl:if test="reverseDescription">
			<crm:P56_bears_feature>
				<crm:E25_Man-Made_Feature>
				<crm:P2_has_type>
				<xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>aspect/reverse</xsl:attribute>
				</crm:P2_has_type>
				<rdfs:label><xsl:attribute name="xsd:string">Reverse description</xsl:attribute></rdfs:label>
				<owl:sameAs>
					<xsl:attribute name="rdf:resource"><xsl:value-of select="$nomismaUri" />reverse</xsl:attribute>
				</owl:sameAs>
				<crmbm:PX.physical_description><xsl:attribute name="xsd:string"><xsl:value-of select="reverseDescription"/></xsl:attribute></crmbm:PX.physical_description>
                </crm:E25_Man-Made_Feature>      
			</crm:P56_bears_feature>
			</xsl:if>
			
			<xsl:if test="reverseLegend">
			<crm:P56_bears_feature>
				<crm:E25_Man-Made_Feature>
				<crm:P2_has_type>
				<xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>inscription</xsl:attribute>
				</crm:P2_has_type>
				<rdfs:label><xsl:attribute name="xsd:string">Reverse legend</xsl:attribute></rdfs:label>
				<crmbm:PX.physical_description><xsl:attribute name="xsd:string"><xsl:value-of select="reverseLegend"/></xsl:attribute></crmbm:PX.physical_description>
				<owl:sameAs>
					<xsl:attribute name="rdf:resource"><xsl:value-of select="$nomismaUri" />reverse</xsl:attribute>
				</owl:sameAs>
                </crm:E25_Man-Made_Feature>      
			</crm:P56_bears_feature>
			</xsl:if>
			
			<!-- Mint mark -->
			
			
			
			<!-- Typings for coin such as reece/abc  -->
			
			<!-- Reece period -->
			<xsl:if test="reeceID">
			<crm:P2_has_type>
				<crm:E55_Type>
					<rdfs:label>Reece period: <xsl:value-of select="reeceID"/></rdfs:label>
					<rdfs:comment>The assigned Reece period assigned for this coin. Only applicable to Roman coins.</rdfs:comment>
					<crm:P2_has_type>
					<xsl:attribute name="rdf:resource"><xsl:value-of select="$findsTerms"/>reeceperiods/id/<xsl:value-of select="reeceID"/></xsl:attribute>
					</crm:P2_has_type>
					<owl:sameAs>
					<xsl:value-of select="$nomismaUri"/>reeceperiod<xsl:value-of select="reeceID"></xsl:value-of>
					</owl:sameAs>
				</crm:E55_Type>
			</crm:P2_has_type>	
			</xsl:if>
			<!--  Medieval category -->
			<xsl:if test="categoryID">
			<crm:P2_has_type>
				<crm:E55_Type>
					<rdfs:label>Medieval category: <xsl:value-of select="categoryTerm"/></rdfs:label>
					<rdfs:comment>A medieval category assigned for breaking down coin types.</rdfs:comment>
					<crm:P2_has_type>
					<xsl:attribute name="rdf:resource"><xsl:value-of select="$findsTerms"/>categories/id/<xsl:value-of select="category"/></xsl:attribute>
					</crm:P2_has_type>
				</crm:E55_Type>
			</crm:P2_has_type>	
			</xsl:if>
			<!-- Medieval type -->
			<xsl:if test="typeID">
			<crm:P2_has_type>
				<crm:E55_Type>
					<rdfs:label>Medieval type: <xsl:value-of select="typeTerm"/></rdfs:label>
					<rdfs:comment>A medieval category type assigned for breaking down coin types.</rdfs:comment>
					<crm:P2_has_type>
					<xsl:attribute name="rdf:resource"><xsl:value-of select="$findsTerms"/>types/id/<xsl:value-of select="type"/></xsl:attribute>
					</crm:P2_has_type>
				</crm:E55_Type>
			</crm:P2_has_type>	
			</xsl:if>
			
			<!-- Reverse type -->
			<xsl:if test="reverse">
			<crm:P2_has_type>
				<crm:E55_Type>
					<rdfs:label>Fourth Century reverse type: <xsl:value-of select="reverseType"/></rdfs:label>
					<rdfs:comment>Roman reverse types by concept</rdfs:comment>
					<crm:P2_has_type>
					<xsl:attribute name="rdf:resource"><xsl:value-of select="$findsTerms"/>reversetypes/id/<xsl:value-of select="reverse"/></xsl:attribute>
					</crm:P2_has_type>
					<rdf:type rdf:resource="http://www.w3.org/2004/02/skos/core#Concept"/>
				</crm:E55_Type>
			</crm:P2_has_type>	
			</xsl:if>
			
			<!-- ABC type  -->
			<xsl:if test="abcType">
			<crm:P2_has_type>
				<crm:E55_Type>
					<rdfs:label>Ancient British Coinage identifier: <xsl:value-of select="abcType"/></rdfs:label>
					<crm:P2_has_type>
					<xsl:attribute name="rdf:resource"><xsl:value-of select="$findsTerms"/>abctypes/id/<xsl:value-of select="abcType"/></xsl:attribute>
					</crm:P2_has_type>
				</crm:E55_Type>
			</crm:P2_has_type>	
			</xsl:if>
			
			<!-- Allen type -->
			<xsl:if test="allenType">
			<crm:P2_has_type>
				<crm:E55_Type>
					<rdfs:label>Fourth Century reverse type: <xsl:value-of select="allenType"/></rdfs:label>
					<crm:P2_has_type>
					<xsl:attribute name="rdf:resource"><xsl:value-of select="$findsTerms"/>allentypes/id/<xsl:value-of select="allenType"/></xsl:attribute>
					</crm:P2_has_type>
				</crm:E55_Type>
			</crm:P2_has_type>	
			</xsl:if>
			
			<!-- Van Arsdell type -->
			<xsl:if test="vaType">
			<crm:P2_has_type>
				<crm:E55_Type>
					<rdfs:label>Fourth Century reverse type: <xsl:value-of select="vaType"/></rdfs:label>
					<crm:P2_has_type>
					<xsl:attribute name="rdf:resource"><xsl:value-of select="$findsTerms"/>vatypes/id/<xsl:value-of select="vaType"/></xsl:attribute>
					</crm:P2_has_type>
				</crm:E55_Type>
			</crm:P2_has_type>	
			</xsl:if>
			
			<!-- end of coin test -->
			</xsl:if>
			
			<!--  an object's inscription -->
			<xsl:if test="inscription">
			<crm:P56_bears_feature>
				<crm:E25_Man-Made_Feature>
					<crm:P2_has_type>
					<xsl:attribute name="rdf:type"><xsl:value-of select="$bmThes"/>inscription</xsl:attribute>
					</crm:P2_has_type>
					<rdfs:label><xsl:attribute name="xsd:string">Object inscription:</xsl:attribute></rdfs:label>
					<crmbm:PX.physical_description><xsl:attribute name="xsd:string"><xsl:value-of select="inscription"/></xsl:attribute></crmbm:PX.physical_description>
				</crm:E25_Man-Made_Feature>      
			</crm:P56_bears_feature>
			</xsl:if>
			
			<!--  Completeness of object -->
			<xsl:if test="completeness">
			<crm:P44_has_condition>
				  <crm:E3_Condition_State>
				    <crm:P2_has_type>
				      <crm:E55_Type>
				      	<xsl:attribute name="rdf:about"><xsl:value-of select="$findsTerms" />conditions/<xsl:value-of select="completeness" /></xsl:attribute>
				      	<rdfs:label>Object completeness: <xsl:value-of select="completenessTerm" /></rdfs:label>
				        <rdf:value><xsl:value-of select="completeness" /></rdf:value>
				      </crm:E55_Type>
				    </crm:P2_has_type>
				  </crm:E3_Condition_State>
			</crm:P44_has_condition>
			</xsl:if>
			
			<xsl:if test="preservation">
			<crm:P44_has_condition>
				  <crm:E3_Condition_State>
				    <crm:P2_has_type>
				      <crm:E55_Type>
				      	<xsl:attribute name="rdf:about"><xsl:value-of select="$findsTerms" />conditions/<xsl:value-of select="preservation" /></xsl:attribute>
				      	<rdfs:label>Object completeness: <xsl:value-of select="preservationTerm" /></rdfs:label>
				        <rdf:value><xsl:value-of select="preservationTerm" /></rdf:value>
				      </crm:E55_Type>
				    </crm:P2_has_type>
				  </crm:E3_Condition_State>
			</crm:P44_has_condition>
			</xsl:if>
			
			<!--  Description -->
			<crm:E5_Event>
				<crm:P2_has_type>
					<crm:E55_Type>
						<xsl:attribute name="xsd:string"><xsl:value-of select="description"/></xsl:attribute>
					</crm:E55_Type>	
				</crm:P2_has_type>
			</crm:E5_Event>
					
		<!--  Primary  -->
        <xsl:if test="material">        
          <crm:P45_consists_of>
              <crm:E57_Material>
                  <xsl:attribute name="rdf:about">http://finds.org.uk/database/terminology/material/id/<xsl:value-of select="material"/></xsl:attribute>
                  <rdfs:label><xsl:attribute name="xsd:string"><xsl:value-of select="materialTerm"/></xsl:attribute></rdfs:label>
                     <!--  <owl:sameAs>
                         <xsl:attribute name="rdf:resource"><xsl:value-of select="$bmThes" />x??????</xsl:attribute>  
                      </owl:sameAs>
                       -->
                      <crm:P1_is_identified_by>
				      <crm:E41_Appellation>
				        	<xsl:attribute name="xsd:string"><xsl:value-of select="materialTerm"/></xsl:attribute>
				      </crm:E41_Appellation>
				    </crm:P1_is_identified_by>
              </crm:E57_Material>
          </crm:P45_consists_of>
        </xsl:if>
		
		<!--  Secondary material -->
        <xsl:if test="secondaryMaterial">        
          <crm:P45_consists_of>
              <crm:E57_Material>
                  <xsl:attribute name="rdf:about">http://finds.org.uk/database/terminology/material/id/<xsl:value-of select="secondaryMaterial"/></xsl:attribute>
                  <rdfs:label><xsl:attribute name="xsd:string"><xsl:value-of select="secondaryMaterialTerm"/></xsl:attribute></rdfs:label>
                     <!-- <owl:sameAs>
                         <xsl:attribute name="rdf:resource"><xsl:value-of select="$bmThes" />x??????</xsl:attribute>  
                      </owl:sameAs> -->
              </crm:E57_Material>
          </crm:P45_consists_of>
        </xsl:if>
        
        <!--  Identifier of object -->
		<crm:P41i_was_classified_by>
		    <crm:E17_Type_Assignment>
		      <crm:P14_carried_out_by>
		        <crm:E39_Actor>
		          <rdfs:label>Created by: <xsl:value-of select="creator"/></rdfs:label>
		          <crm:P131.is_identified_by >
					  <crm:E82.Actor_Appellation>
					    <xsl:attribute name="xsd:string"><xsl:value-of select="creator"/></xsl:attribute>
					  </crm:E82.Actor_Appellation>
				</crm:P131.is_identified_by>
		        </crm:E39_Actor>
		      </crm:P14_carried_out_by>
		    </crm:E17_Type_Assignment>
		</crm:P41i_was_classified_by>
		
		<!--  Recorder of object -->	
		<crm:P41i_was_classified_by>
		    <crm:E17_Type_Assignment>
		      <crm:P14_carried_out_by>
		        <crm:E39_Actor>
		          <rdfs:label>Recorded by: <xsl:value-of select="recorder"/></rdfs:label>
		          <crm:P131.is_identified_by >
					  <crm:E82.Actor_Appellation>
					    <xsl:attribute name="xsd:string"><xsl:value-of select="recorder"/></xsl:attribute>
					  </crm:E82.Actor_Appellation>
				</crm:P131.is_identified_by>
		        </crm:E39_Actor>
		      </crm:P14_carried_out_by>
		    </crm:E17_Type_Assignment>
		</crm:P41i_was_classified_by>
		
		<!--  Identifier of object -->
		<xsl:if test="identifier">
		<crm:P41i_was_classified_by>
		    <crm:E17_Type_Assignment>
		      <crm:P14_carried_out_by>
		        <crm:E39_Actor>
		          <rdfs:label>Identified by: <xsl:value-of select="identifier"/></rdfs:label>
		          <crm:P131.is_identified_by >
					  <crm:E82.Actor_Appellation>
					    <xsl:attribute name="xsd:string"><xsl:value-of select="identifier"/></xsl:attribute>
					  </crm:E82.Actor_Appellation>
				</crm:P131.is_identified_by>
		        </crm:E39_Actor>
		      </crm:P14_carried_out_by>
		    </crm:E17_Type_Assignment>
		</crm:P41i_was_classified_by>
		</xsl:if>
		
		<!--  Secondary Identifier of object -->
		<xsl:if test="secondaryIdentifier">
		<crm:P41i_was_classified_by>
		    <crm:E17_Type_Assignment>
		      <crm:P14_carried_out_by>
		        <crm:E39_Actor>
		          <rdfs:label>Identified by: <xsl:value-of select="secondaryIdentifier"/></rdfs:label>
		          <crm:P131.is_identified_by >
					  <crm:E82.Actor_Appellation>
					    <xsl:attribute name="xsd:string"><xsl:value-of select="secondaryIdentifier"/></xsl:attribute>
					  </crm:E82.Actor_Appellation>
				</crm:P131.is_identified_by>
		        </crm:E39_Actor>
		      </crm:P14_carried_out_by>
		    </crm:E17_Type_Assignment>
		</crm:P41i_was_classified_by>
		</xsl:if>
		
		<!--  Institution recording the object -->
		<crm:P41i_was_classified_by>
		    <crm:E17_Type_Assignment>
		      <crm:P14_carried_out_by>
		        <crm:E39_Actor>
		          <rdfs:label>Recording institution: <xsl:value-of select="institution"/></rdfs:label>
		          <crm:P131.is_identified_by >
					  <crm:E82.Actor_Appellation>
					    <xsl:attribute name="xsd:string"><xsl:value-of select="institution"/></xsl:attribute>
					  </crm:E82.Actor_Appellation>
				</crm:P131.is_identified_by>
		        </crm:E39_Actor>
		      </crm:P14_carried_out_by>
		    </crm:E17_Type_Assignment>
		</crm:P41i_was_classified_by>	
		<!-- Method of production -->
		
                    
        <!--  Method of decoration -->
            
        <!--  End of CIDOC-CRM rdf -->     
      
		
		</crm:E22_Man-Made_Object>
		</foaf:primaryTopic>
	</foaf:Document> 
 
	<xsl:if test="objecttype = 'COIN'" >
			<nm:coin>
				
				<xsl:attribute name="rdf:about"><xsl:value-of select="$url"/><xsl:value-of select="id"/></xsl:attribute> 
				
				<dcterms:title>
					<xsl:attribute name="xsd:string"><xsl:value-of select="old_findID" /></xsl:attribute>
				</dcterms:title>
				
				<dcterms:identifier>
					<xsl:attribute name="xsd:string"><xsl:value-of select="id" /></xsl:attribute>
				</dcterms:identifier>
				
				<nm:collection>
					<xsl:attribute name="xsd:string">The Portable Antiquities Scheme</xsl:attribute>
				</nm:collection>
				
  				<xsl:if test="broadperiod = 'ROMAN'">
				<dcterms:partOf rdf:resource="http://nomisma.org/id/roman_numismatics"/>
                </xsl:if>
				
				<xsl:if test="broadperiod = 'BYZANTINE'">
				<dcterms:partOf rdf:resource="http://nomisma.org/id/byzantine_numismatics"/>
                </xsl:if>
				
				<xsl:if test="broadperiod = 'GREEK AND ROMAN PROVINCIAL'">
				<dcterms:partOf rdf:resource="http://nomisma.org/id/greek_numismatics"/>
                </xsl:if>
				
				<nm:numismatic_term rdf:resource="http://nomisma.org/id/coin"/>
				
				<nm:collection>
                	<xsl:attribute name="xsd:string">Portable Antiquities Scheme</xsl:attribute>
				</nm:collection>
				
				<xsl:if test="axis" >
				<nm:axis rdf:datatype="xsd:integer"><xsl:value-of select="axis" /></nm:axis>
				</xsl:if>
				
				<xsl:if test="diameter">				
				<nm:diameter>
					<rdf:Description>
						<rdf:value rdf:datatype="xsd:decimal"><xsl:value-of select="diameter"/></rdf:value>
						<nm:units rdf:resource="http://qudt.org/vocab/unit#Millimeter" />
					</rdf:Description>
				</nm:diameter>
				</xsl:if>
				
				<xsl:if test="weight">
				<nm:weight >
					<rdf:Description>
						<rdf:value rdf:datatype="xsd:decimal"><xsl:value-of select="weight"/></rdf:value>
						<nm:units rdf:resource="http://qudt.org/vocab/unit#Gram" />
					</rdf:Description>
				</nm:weight>
				</xsl:if>
				
				<xsl:if test="thickness">
				<nm:thickness>
					<rdf:Description>
					<rdf:value rdf:datatype="xsd:decimal"><xsl:value-of select="thickness"/></rdf:value>
						<nm:units rdf:resource="http://qudt.org/vocab/unit#Millimeter" />
					</rdf:Description>
				</nm:thickness>
				</xsl:if>
				
				<xsl:if test="fromdate">
				<nm:start_date rdf:datatype="xsd:gYear"><xsl:value-of select="fromdate"/></nm:start_date>
				</xsl:if>
				
				<xsl:if test="todate">
				<nm:end_date rdf:datatype="xsd:gYear"><xsl:value-of select="todate"/></nm:end_date>
				</xsl:if>
				
				<xsl:if test="denominationName">
				<nm:denomination>
					<rdf:Description>
						<xsl:attribute name="xsd:string"><xsl:value-of select="denominationName"/></xsl:attribute>
						<xsl:if test="denominationDbpedia">
						<owl:sameAs><xsl:value-of select="$dbpediaUri" /><xsl:value-of select="denominationDbpedia"/></owl:sameAs>
						</xsl:if>				
					</rdf:Description>
				</nm:denomination>
				</xsl:if>
				
				<xsl:if test="reverseDescription">
				<nm:reverse>
					<nm:description>
						<xsl:attribute name="xsd:string">
							<xsl:value-of select="reverseDescription"/>
						</xsl:attribute>
					</nm:description>
				</nm:reverse>
				</xsl:if>
				
				<xsl:if test="reverseLegend">
				<nm:reverse>
					<nm:legend>
						<xsl:attribute name="xsd:string">
							<xsl:value-of select="reverseLegend"/>
						</xsl:attribute>
					</nm:legend>	
				</nm:reverse>
				</xsl:if>
				
				<xsl:if test="obverseDescription">
				<nm:obverse>
					<nm:description>
						<xsl:attribute name="xsd:string">
							<xsl:value-of select="obverseDescription"/>
						</xsl:attribute>
					</nm:description>
				</nm:obverse>
				</xsl:if>
				
				<xsl:if test="obverseLegend">
				<nm:obverse>
					<nm:legend>
						<xsl:attribute name="xsd:string">
							<xsl:value-of select="obverseLegend"/>
						</xsl:attribute>
					</nm:legend>	
				</nm:obverse>
				</xsl:if>
				
				<xsl:if test="ruler">
				<nm:authority>
					<rdf:Description>
					<xsl:attribute name="rdf:about">http://finds.org.uk/database/terminology/rulers/ruler/id/<xsl:value-of select="ruler"/></xsl:attribute> 
						<rdfs:label rdf:datatype="xsd:string"><xsl:value-of select="rulerName"/></rdfs:label>
						<xsl:if test="rulerDbpedia">
						<owl:sameAs >
							<xsl:attribute name="rdf:resource"><xsl:value-of select="$dbpediaUri" /><xsl:value-of select="rulerDbpedia"/></xsl:attribute>
						</owl:sameAs>
						</xsl:if>
						<xsl:if test="rulerViaf">
						<owl:sameAs >
							<xsl:attribute name="rdf:resource">http://viaf.org/viaf/<xsl:value-of select="rulerViaf"/></xsl:attribute>
						</owl:sameAs>
						</xsl:if>
						<xsl:if test="rulerNomisma">
						<owl:sameAs>
							<xsl:attribute name="rdf:resource"><xsl:value-of select="$nomismaUri" /><xsl:value-of select="rulerNomisma"/></xsl:attribute>
						</owl:sameAs>
						</xsl:if>
					</rdf:Description>
				</nm:authority>
				</xsl:if>
				
				<xsl:choose>
					<xsl:when test="not(knownas)" >
						<nm:findspot>
						      <rdf:Description>
						      	<gn:parentCountry rdf:resource="http://www.geonames.org/2635167"/>
						      	<geo:lat><xsl:attribute name="xsd:decimal"><xsl:value-of select="fourFigureLat"/></xsl:attribute></geo:lat>
								<geo:long><xsl:attribute name="xsd:decimal"><xsl:value-of select="fourFigureLon"/></xsl:attribute></geo:long>
								<geo:lat_long><xsl:attribute name="xsd:string"><xsl:value-of select="fourFigureLat"/>,<xsl:value-of select="fourFigureLon"/></xsl:attribute></geo:lat_long>
								<xsl:if test="elevation">
								<crmeh:EXP5.spatial_z>
									<rdf:Description>
							      		<rdf:value><xsl:attribute name="xsd:decimal"><xsl:value-of select="elevation"/></xsl:attribute></rdf:value>
							      		<rdfs:label>
							      			<xsl:attribute name="xsd:string">Elevation above/below sea level: <xsl:value-of select="elevation"/></xsl:attribute>
							      		</rdfs:label>
						      		</rdf:Description>
								</crmeh:EXP5.spatial_z>
								</xsl:if>
								<xsl:if test="accuracy">
								<pas:accuracy>
									<rdf:Description>
										<rdf:value><xsl:attribute name="xsd:integer"><xsl:value-of select="accuracy"/></xsl:attribute></rdf:value>
										<rdfs:label>
											<xsl:attribute name="xsd:string">Coordinates place object within a <xsl:value-of select="accuracy"/> metre square</xsl:attribute>
										</rdfs:label>
									</rdf:Description>
								</pas:accuracy>
								</xsl:if>
								<xsl:if test="precision">
								<pas:coordinatePrecision>
						      		<rdf:Description>
							      		<rdf:value><xsl:attribute name="xsd:integer"><xsl:value-of select="precision"/></xsl:attribute></rdf:value>
							      		<rdfs:label>
							      			<xsl:attribute name="xsd:string">Grid reference length of <xsl:value-of select="precision"/> figures</xsl:attribute>
							      		</rdfs:label>
						      		</rdf:Description>
						      	</pas:coordinatePrecision>
						      	</xsl:if>								
 								<xsl:if test="county">
							    <osAdminGeo:county>
							      	<rdf:Description>
							      	<xsl:attribute name="rdf:about"><xsl:value-of select="$osUri"/><xsl:value-of select="countyID"/></xsl:attribute>
							  			<rdfs:label>
							  				<xsl:attribute name="xsd:string"><xsl:value-of select="county"/></xsl:attribute>
							  			</rdfs:label>
							    	</rdf:Description>
							    </osAdminGeo:county>
							    </xsl:if>
							    <xsl:if test="district">
								<osAdminGeo:district>
								<rdf:Description>
							      	<xsl:attribute name="rdf:about"><xsl:value-of select="$osUri"/><xsl:value-of select="districtID"/></xsl:attribute>
							  			<rdfs:label>
							  				<xsl:attribute name="xsd:string"><xsl:value-of select="district"/></xsl:attribute>
							  			</rdfs:label>
							  	</rdf:Description>
								</osAdminGeo:district>
								</xsl:if>								

								<osAdminGeo:parish><xsl:attribute name="xsd:string"><xsl:value-of select="parish"/></xsl:attribute></osAdminGeo:parish>
								<xsl:if test="woeid">
								<owl:sameAs>
				        			<xsl:attribute name="rdf:resource">http://woe.spum.org/id/<xsl:value-of select="woeid"/></xsl:attribute>
				       			</owl:sameAs>
				       			</xsl:if>
				       			<xsl:if test="parishGeonames">
				       			<owl:sameAs>
				        			<xsl:attribute name="rdf:resource">http://www.geonames.org/<xsl:value-of select="parishGeonames"/></xsl:attribute>
				       			</owl:sameAs>
				       			</xsl:if>
				       			<xsl:if test="parishID">
				       			<owl:sameAs>
									<xsl:attribute name="rdf:resource"><xsl:value-of select="$osUri"/><xsl:value-of select="parishID"/></xsl:attribute>
				       			</owl:sameAs>
				       			</xsl:if>
				       			<xsl:if test="osmNode">
				       			<owl:sameAs>
									<xsl:attribute name="rdf:resource">http://www.openstreetmap.org/browse/node/<xsl:value-of select="osmNode"/></xsl:attribute>
				       			</owl:sameAs>
				       			</xsl:if>

				      		<xsl:if test="parish != ''">
									    <rdfs:label>
									    	<xsl:attribute name="xsd:string"><xsl:value-of select="parish"/></xsl:attribute>
									    </rdfs:label>
									    <xsl:if test="parishType">
									    <rdf:type>
									    <xsl:attribute name="rdf:resource">http://data.ordnancesurvey.co.uk/ontology/admingeo/<xsl:value-of select="parishType"/></xsl:attribute>
									    </rdf:type>
									    </xsl:if>
									    
									    <xsl:if test="regionID">
									    <osAdminGeo:inEuropeanRegion>
									    <xsl:attribute name="rdf:resource"><xsl:value-of select="$osUri"/><xsl:value-of select="regionID"/></xsl:attribute>
									    </osAdminGeo:inEuropeanRegion>
									    </xsl:if>
									    
									    <xsl:if test="countyID">
									    <osSpatialRel:within>
									    <xsl:attribute name="rdf:resource"><xsl:value-of select="$osUri"/><xsl:value-of select="countyID"/></xsl:attribute>
									    </osSpatialRel:within>
									    </xsl:if>
									    
									    <xsl:if test="districtID">
									    <osAdminGeo:inDistrict>
									    <xsl:attribute name="rdf:resource"><xsl:value-of select="$osUri"/><xsl:value-of select="districtID"/></xsl:attribute>
									    </osAdminGeo:inDistrict>
									    </xsl:if>
				       			</xsl:if>
							</rdf:Description>
						</nm:findspot>
					</xsl:when>
					<xsl:otherwise>
						<nm:findspot>
						      <rdf:Description>
						      	<gn:parentCountry rdf:resource="http://www.geonames.org/2635167"/>
						      	<xsl:if test="accuracy">
								<pas:accuracy>
									<rdf:Description>
										<rdf:value><xsl:attribute name="xsd:integer"><xsl:value-of select="accuracy"/></xsl:attribute></rdf:value>
										<rdfs:label>
											<xsl:attribute name="xsd:string">Coordinates place object within a <xsl:value-of select="precision"/> metre square</xsl:attribute>
										</rdfs:label>
									</rdf:Description>
								</pas:accuracy>
								</xsl:if>
								<xsl:if test="precision">
						      	<pas:coordinatePrecision>
						      		<rdf:Description>
							      		<rdf:value><xsl:attribute name="xsd:integer"><xsl:value-of select="precision"/></xsl:attribute></rdf:value>
							      		<rdfs:label>
							      			<xsl:attribute name="xsd:string">Grid reference length <xsl:value-of select="precision"/> figures</xsl:attribute>
							      		</rdfs:label>
						      		</rdf:Description>
						      	</pas:coordinatePrecision>
						      	</xsl:if>
						      	<xsl:if test="regionID">
						      	<osAdminGeo:inEuropeanRegion>
							    <xsl:attribute name="rdf:resource"><xsl:value-of select="$osUri"/><xsl:value-of select="regionID"/></xsl:attribute>
							    </osAdminGeo:inEuropeanRegion>
							    </xsl:if>
							    <xsl:if test="countyID">
							    <osSpatialRel:within>
							    <xsl:attribute name="rdf:resource"><xsl:value-of select="$osUri"/><xsl:value-of select="countyID"/></xsl:attribute>
							    </osSpatialRel:within>
							    </xsl:if>
							    <xsl:if test="districtID">
							    <osAdminGeo:inDistrict>
							    <xsl:attribute name="rdf:resource"><xsl:value-of select="$osUri"/><xsl:value-of select="districtID"/></xsl:attribute>
							    </osAdminGeo:inDistrict>
							    </xsl:if>
							    <xsl:if test="county">
							    <osAdminGeo:county>
							      	<rdf:Description>
							      	<xsl:attribute name="rdf:about"><xsl:value-of select="$osUri"/><xsl:value-of select="countyID"/></xsl:attribute>
							  			<rdfs:label>
							  				<xsl:attribute name="xsd:string"><xsl:value-of select="county"/></xsl:attribute>
							  			</rdfs:label>
							    	</rdf:Description>
							    </osAdminGeo:county>
							    </xsl:if>
							    <xsl:if test="district">
								<osAdminGeo:district>
								<rdf:Description>
							      	<xsl:attribute name="rdf:about"><xsl:value-of select="$osUri"/><xsl:value-of select="districtID"/></xsl:attribute>
							  			<rdfs:label>
							  				<xsl:attribute name="xsd:string"><xsl:value-of select="district"/></xsl:attribute>
							  			</rdfs:label>
							  	</rdf:Description>
								</osAdminGeo:district>
								</xsl:if>
								<xsl:if test="knownas">
								<pas:knownas><xsl:attribute name="xsd:string"><xsl:value-of select="knownas"/></xsl:attribute></pas:knownas>
								</xsl:if>
						      </rdf:Description>
						</nm:findspot>
					</xsl:otherwise>
				</xsl:choose>
				
				<xsl:if test="mintName != ''" >
					<nm:mint>
					<rdf:Description>
					<xsl:attribute name="rdf:about">http://finds.org.uk/database/terminology/mints/mint/id/<xsl:value-of select="mint"/></xsl:attribute>
						<rdfs:label rdf:datatype="xsd:string">Mint attributed: <xsl:value-of select="mintName"/></rdfs:label> 
						<owl:sameAs>
							<xsl:attribute name="rdf:resource"><xsl:value-of select="$nomismaUri" /><xsl:value-of select="nomismaMintID"/></xsl:attribute>
						</owl:sameAs>
						<owl:sameAs>
							<xsl:attribute name="rdf:resource">http://woe.spum.org/id/<xsl:value-of select="mintWoeid"/></xsl:attribute>
						</owl:sameAs>
						<owl:sameAs>
							<xsl:attribute name="rdf:resource">http://geonames.org/<xsl:value-of select="mintGeonamesID"/></xsl:attribute>
						</owl:sameAs>
						<owl:sameAs >
							<xsl:attribute name="rdf:resource"><xsl:value-of select="$pleiadesUri" /><xsl:value-of select="pleiadesID"/>#this</xsl:attribute>
						</owl:sameAs>
					</rdf:Description>
					</nm:mint>
				</xsl:if>	
				
				<xsl:if test="moneyer">	
				<nm:moneyer>
					<rdf:Description>
					<xsl:attribute name="rdf:about">http://finds.org.uk/database/terminology/moneyers/moneyer/id/<xsl:value-of select="mint_id"/></xsl:attribute>
					<rdfs:label rdf:datatype="xsd:string">Moneyer attributed: <xsl:value-of select="moneyerName"/></rdfs:label>
					<xsl:if test="moneyerDbpedia">
						<owl:sameAs >
							<xsl:attribute name="rdf:resource"><xsl:value-of select="$dbpediaUri" /><xsl:value-of select="moneyerDbpedia"/></xsl:attribute>
						</owl:sameAs>
					</xsl:if>
					</rdf:Description>
				</nm:moneyer>
				</xsl:if>
				
				<xsl:if test="reeceID">			
				<nm:reecePeriod>
					<rdf:Description>
					<xsl:attribute name="rdf:about">http://finds.org.uk/romancoins/reeceperiods/period/id/<xsl:value-of select="reeceID"/></xsl:attribute>
					<rdfs:label rdf:datatype="xsd:string">Reece period <xsl:value-of select="reeceID"/></rdfs:label>
					<owl:sameAs>
						<xsl:attribute name="rdf:resource"><xsl:value-of select="$nomismaUri" />reeceperiod<xsl:value-of select="reeceID"/></xsl:attribute>
					</owl:sameAs>
					</rdf:Description>
				</nm:reecePeriod>
				</xsl:if>
				
				<xsl:if test="reverseType">	
				<nm:reverseType>
					<rdf:Description>
					<xsl:attribute name="rdf:about">http://finds.org.uk/romancoins/reversetypes/type/id/<xsl:value-of select="reverse"/></xsl:attribute>
						<rdfs:label rdf:datatype="xsd:string"><xsl:value-of select="reverseType" /></rdfs:label>
					</rdf:Description>
				</nm:reverseType>
				</xsl:if>	
				
				<xsl:if test="thumbnail">
				<nm:thumbnail>
					<rdf:Description>
					<xsl:attribute name="rdf:about"><xsl:value-of select="$thumb"/><xsl:value-of select="thumbnail"/>.jpg</xsl:attribute>
						<rdfs:label>
							<xsl:attribute name="xsd:string">Thumbnail image of <xsl:value-of select="old_findID"/></xsl:attribute>
						</rdfs:label>
					</rdf:Description>
				</nm:thumbnail>
				</xsl:if>
				
				<xsl:if test="materialTerm">	
				<nm:material>
					<rdf:Description>
						<xsl:attribute name="rdf:about">http://finds.org.uk/database/terminology/material/id/<xsl:value-of select="material"/></xsl:attribute>
						<rdfs:label>
							<xsl:attribute name="xsd:string"><xsl:value-of select="materialTerm"/></xsl:attribute>
						</rdfs:label>
					</rdf:Description>
				</nm:material>
				</xsl:if>	
				
				<!-- Type series for coins -->
				<xsl:if test="cciNumber">
				<nm:type_series_item>
					<rdf:Description>
						<xsl:attribute name="rdf:about">http://finds.org.uk/ironagecoins/cci/id/<xsl:value-of select="cciNumber"/></xsl:attribute>
						<rdfs:label>
							<xsl:attribute name="xsd:string">Celtic Coin Index number: <xsl:value-of select="cciNumber"/></xsl:attribute>
						</rdfs:label>
						<rdf:value>
							<xsl:attribute name="xsd:decimal"><xsl:value-of select="cciNumber"/></xsl:attribute>
						</rdf:value>
						<rdfs:comment>No identifier in nomisma for cciNumbers</rdfs:comment>
					</rdf:Description>
				</nm:type_series_item>
				</xsl:if>
				
				<xsl:if test="vaType">
				<nm:type_series_item>
					<rdf:Description>
						<xsl:attribute name="rdf:about">http://finds.org.uk/ironagecoins/vatypes/type/<xsl:value-of select="vaType"/></xsl:attribute>
						<rdfs:label>
							<xsl:attribute name="xsd:string">Van Arsdell type: <xsl:value-of select="vaType"/></xsl:attribute>
						</rdfs:label>
						<rdf:value>
							<xsl:attribute name="xsd:string"><xsl:value-of select="vaType"/></xsl:attribute>
						</rdf:value>
					</rdf:Description>
				</nm:type_series_item>
				</xsl:if>
				
				<xsl:if test="abcType">
				<nm:type_series_item>
					<rdf:Description>
						<xsl:attribute name="rdf:about">http://finds.org.uk/ironagecoins/abctypes/type/<xsl:value-of select="abcType"/></xsl:attribute>
						<rdfs:label>
							<xsl:attribute name="xsd:string">ABC type number: <xsl:value-of select="abcType"/></xsl:attribute>
						</rdfs:label>
					</rdf:Description>
				</nm:type_series_item>
				</xsl:if>	
					
			</nm:coin>
		</xsl:if> <!--  end of test for a coin -->
	</xsl:template>

</xsl:stylesheet>