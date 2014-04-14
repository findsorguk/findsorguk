<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema" exclude-result-prefixes="xs xsl" version="2.0"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:oac="http://www.openannotation.org/ns/" xmlns:owl="http://www.w3.org/2002/07/owl#">
	<xsl:output method="xml" encoding="UTF-8" indent="yes"/>

	<xsl:param name="url">
		<xsl:value-of select="http://finds.org.uk/database/artefacts/record/"/>
	</xsl:param>

	<xsl:template match="/">
		<rdf:RDF>
			<xsl:apply-templates select="//doc"/>
		</rdf:RDF>
	</xsl:template>

	<xsl:template match="doc">
		<oac:Annotation rdf:about="{$url}pelagios.rdf#{str[@name='id']}">
			<dcterms:title>
				<xsl:value-of select="str[@name='title_display']"/>
			</dcterms:title>
			<xsl:for-each select="arr[@name='pleiadesID']/str">
				<oac:hasBody rdf:resource="{.}#this"/>
			</xsl:for-each>
			<owl:sameAs rdf:resource="http://nomisma.org/id/{str[@name='mintNomisma']}"/>
			<oac:hasTarget rdf:resource="{$url}id/{str[@name='id']}"/>
		</oac:Annotation>
	</xsl:template>
</xsl:stylesheet>