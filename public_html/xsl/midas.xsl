<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.heritage-standards.org.uk/midas/schema/2.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.heritage-standards.org.uk/midas/schema/2.0 http://www.heritage-standards.org.uk/midas/schema/2.0/midas_object.xsd">
	<xsl:output method="xml" encoding="UTF-8" indent="yes"/>

	<xsl:param name="url">
		<xsl:value-of select="http://finds.org.uk/database/artefacts/record/"/>
	</xsl:param>

	<xsl:template match="/">
		<objects>
			<xsl:apply-templates select="//doc"/>
		</objects>
	</xsl:template>

</xsl:stylesheet>